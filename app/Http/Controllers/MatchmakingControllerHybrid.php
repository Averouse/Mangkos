<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalApplication;
use App\Models\MatchmakingProfile;
use App\Models\UserMatch;
use App\Models\User;
use App\Models\Kost;
use Auth;

class MatchmakingController extends Controller
{
    // Pre-calculated AHP weights from expert judgment
    private $defaultWeights = [0.364, 0.222, 0.148, 0.088, 0.088, 0.054, 0.036];
    
    public function index()
    {
        $user = Auth::user();
        
        if ($user->status !== 'approved') {
            return view('user.matchmaking-locked', [
                'reason' => 'identity',
                'message' => 'Identitas Anda belum diverifikasi oleh admin. Silakan tunggu proses verifikasi.'
            ]);
        }
        
        $approvedKosts = RentalApplication::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('kost')
            ->get();
            
        if ($approvedKosts->isEmpty()) {
            return view('user.matchmaking-locked', [
                'reason' => 'no_kost',
                'message' => 'Anda belum memiliki kost yang disetujui. Ajukan sewa kost terlebih dahulu.'
            ]);
        }
        
        return view('user.matchmaking', [
            'approvedKosts' => $approvedKosts
        ]);
    }
    
    public function selectKost($kostId)
    {
        $user = Auth::user();
        
        $application = RentalApplication::where('user_id', $user->id)
            ->where('kost_id', $kostId)
            ->where('status', 'approved')
            ->first();
            
        if (!$application) {
            return redirect()->route('matchmaking.index')
                ->with('error', 'Anda tidak memiliki akses ke kost ini');
        }
        
        return view('user.matchmaking', [
            'kostId' => $kostId,
            'kost' => $application->kost
        ]);
    }
    
    public function saveProfile(Request $request)
    {
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'budget' => 'required|integer|min:1|max:5',
            'smoke' => 'required|in:yes,no',
            'clean' => 'required|integer|min:1|max:5',
            'sleep' => 'required|in:early,late',
            'noise' => 'required|integer|min:1|max:5',
            'social' => 'required|in:introvert,ambivert,extrovert',
            'worship' => 'required|in:flexible,strict'
        ]);
        
        $preferences = [
            'budget' => $validated['budget'],
            'smoke' => $validated['smoke'],
            'clean' => $validated['clean'],
            'sleep' => $validated['sleep'],
            'noise' => $validated['noise'],
            'social' => $validated['social'],
            'worship' => $validated['worship']
        ];
        
        $profile = MatchmakingProfile::updateOrCreate(
            ['user_id' => Auth::id(), 'kost_id' => $validated['kost_id']],
            [
                'preferences' => $preferences, 
                'ahp_weights' => $this->defaultWeights,
                'is_visible' => true
            ]
        );
        
        // Use simplified TOPSIS for performance
        $this->calculateTOPSISMatches($validated['kost_id'], Auth::id());
        
        return response()->json(['success' => true]);
    }
    
    public function toggleVisibility(Request $request)
    {
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'is_visible' => 'required|boolean'
        ]);
        
        $profile = MatchmakingProfile::where('user_id', Auth::id())
            ->where('kost_id', $validated['kost_id'])
            ->first();
            
        if ($profile) {
            $profile->is_visible = $validated['is_visible'];
            $profile->save();
            
            if ($validated['is_visible']) {
                $this->calculateTOPSISMatches($validated['kost_id'], Auth::id());
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    public function results($kostId)
    {
        $user = Auth::user();
        
        $myProfile = MatchmakingProfile::where('user_id', $user->id)
            ->where('kost_id', $kostId)
            ->first();
            
        if (!$myProfile) {
            return redirect()->route('matchmaking.select', $kostId);
        }
        
        $matches = UserMatch::where('kost_id', $kostId)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->with(['user1', 'user2'])
            ->orderBy('compatibility_score', 'desc')
            ->get();
            
        $kost = Kost::findOrFail($kostId);
        
        return view('user.matchmaking-results', [
            'matches' => $matches,
            'kost' => $kost
        ]);
    }
    
    /**
     * Simplified TOPSIS implementation for performance
     */
    private function calculateTOPSISMatches($kostId, $userId)
    {
        try {
            $myProfile = MatchmakingProfile::where('user_id', $userId)
                ->where('kost_id', $kostId)
                ->first();
                
            if (!$myProfile) return;
            
            $otherProfiles = MatchmakingProfile::where('kost_id', $kostId)
                ->where('user_id', '!=', $userId)
                ->where('is_visible', true)
                ->get();
            
            if ($otherProfiles->isEmpty()) return;
            
            // Build decision matrix
            $candidates = [];
            $candidateIds = [];
            
            foreach ($otherProfiles as $profile) {
                $candidates[] = $this->preferencesToVector($profile->preferences);
                $candidateIds[] = $profile->user_id;
            }
            
            // Apply simplified TOPSIS
            $scores = $this->simplifiedTOPSIS($candidates, $this->defaultWeights);
            
            // Save matches
            foreach ($scores as $index => $score) {
                $otherUserId = $candidateIds[$index];
                
                UserMatch::updateOrCreate(
                    [
                        'user1_id' => min($userId, $otherUserId),
                        'user2_id' => max($userId, $otherUserId),
                        'kost_id' => $kostId
                    ],
                    [
                        'compatibility_score' => round($score * 100, 2),
                        'status' => 'pending'
                    ]
                );
            }
        } catch (\Exception $e) {
            \Log::error('TOPSIS calculation error: ' . $e->getMessage());
        }
    }
    
    /**
     * Simplified TOPSIS - faster than full implementation
     */
    private function simplifiedTOPSIS($candidates, $weights)
    {
        $m = count($candidates);
        $n = count($candidates[0]);
        
        // Step 1: Normalize (simplified)
        $normalized = [];
        for ($j = 0; $j < $n; $j++) {
            $column = array_column($candidates, $j);
            $max = max($column);
            $min = min($column);
            $range = $max - $min;
            
            for ($i = 0; $i < $m; $i++) {
                if ($range > 0) {
                    $normalized[$i][$j] = ($candidates[$i][$j] - $min) / $range;
                } else {
                    $normalized[$i][$j] = 1; // All same values
                }
            }
        }
        
        // Step 2: Apply weights
        $weighted = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
            }
        }
        
        // Step 3: Calculate ideal solutions
        $idealPositive = [];
        $idealNegative = [];
        for ($j = 0; $j < $n; $j++) {
            $column = array_column($weighted, $j);
            $idealPositive[$j] = max($column);
            $idealNegative[$j] = min($column);
        }
        
        // Step 4: Calculate closeness coefficient
        $scores = [];
        for ($i = 0; $i < $m; $i++) {
            $distancePos = 0;
            $distanceNeg = 0;
            
            for ($j = 0; $j < $n; $j++) {
                $distancePos += pow($weighted[$i][$j] - $idealPositive[$j], 2);
                $distanceNeg += pow($weighted[$i][$j] - $idealNegative[$j], 2);
            }
            
            $distancePos = sqrt($distancePos);
            $distanceNeg = sqrt($distanceNeg);
            
            $scores[$i] = $distanceNeg / ($distancePos + $distanceNeg);
        }
        
        return $scores;
    }
    
    private function preferencesToVector($preferences)
    {
        return [
            $preferences['budget'],
            $preferences['smoke'] === 'yes' ? 1 : 0,
            $preferences['clean'],
            $preferences['sleep'] === 'early' ? 1 : 0,
            $preferences['noise'],
            $this->socialToNumeric($preferences['social']),
            $preferences['worship'] === 'strict' ? 1 : 0
        ];
    }
    
    private function socialToNumeric($social)
    {
        $map = ['introvert' => 1, 'ambivert' => 3, 'extrovert' => 5];
        return $map[$social] ?? 3;
    }
}