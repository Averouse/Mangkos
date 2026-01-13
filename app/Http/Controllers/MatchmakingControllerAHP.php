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
    // AHP Random Index values for consistency checking
    private $randomIndex = [
        1 => 0, 2 => 0, 3 => 0.58, 4 => 0.9, 5 => 1.12, 
        6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49
    ];

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
            'worship' => 'required|in:flexible,strict',
            // AHP pairwise comparison matrix (optional - use defaults if not provided)
            'pairwise_matrix' => 'nullable|array'
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
        
        // Calculate AHP weights from pairwise matrix or use defaults
        $weights = $this->calculateAHPWeights($validated['pairwise_matrix'] ?? null);
        
        $profile = MatchmakingProfile::updateOrCreate(
            ['user_id' => Auth::id(), 'kost_id' => $validated['kost_id']],
            [
                'preferences' => $preferences, 
                'ahp_weights' => $weights,
                'is_visible' => true
            ]
        );
        
        $this->calculateMatches($validated['kost_id'], Auth::id());
        
        $otherProfiles = MatchmakingProfile::where('kost_id', $validated['kost_id'])
            ->where('user_id', '!=', Auth::id())
            ->get();
            
        foreach ($otherProfiles as $otherProfile) {
            $this->calculateMatches($validated['kost_id'], $otherProfile->user_id);
        }
        
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
                $this->calculateMatches($validated['kost_id'], Auth::id());
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
     * Calculate AHP weights from pairwise comparison matrix
     */
    private function calculateAHPWeights($pairwiseMatrix = null)
    {
        // Default pairwise comparison matrix if none provided
        if (!$pairwiseMatrix) {
            $pairwiseMatrix = [
                [1, 2, 3, 5, 5, 7, 9],      // Budget
                [0.5, 1, 2, 3, 3, 5, 7],    // Smoke
                [0.33, 0.5, 1, 2, 2, 3, 5], // Clean
                [0.2, 0.33, 0.5, 1, 1, 2, 3], // Sleep
                [0.2, 0.33, 0.5, 1, 1, 2, 3], // Noise
                [0.14, 0.2, 0.33, 0.5, 0.5, 1, 2], // Social
                [0.11, 0.14, 0.2, 0.33, 0.33, 0.5, 1] // Worship
            ];
        }
        
        $n = count($pairwiseMatrix);
        
        // Step 1: Calculate column sums
        $columnSums = array_fill(0, $n, 0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $columnSums[$j] += $pairwiseMatrix[$i][$j];
            }
        }
        
        // Step 2: Normalize matrix
        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $pairwiseMatrix[$i][$j] / $columnSums[$j];
            }
        }
        
        // Step 3: Calculate priority vector (row averages)
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $weights[$i] = array_sum($normalizedMatrix[$i]) / $n;
        }
        
        // Step 4: Consistency check
        $consistencyRatio = $this->calculateConsistencyRatio($pairwiseMatrix, $weights);
        
        if ($consistencyRatio > 0.1) {
            \Log::warning("AHP Consistency Ratio too high: {$consistencyRatio}. Using default weights.");
            // Return default weights if inconsistent
            return [0.30, 0.20, 0.15, 0.10, 0.10, 0.10, 0.05];
        }
        
        return $weights;
    }
    
    /**
     * Calculate Consistency Ratio for AHP
     */
    private function calculateConsistencyRatio($matrix, $weights)
    {
        $n = count($matrix);
        
        // Calculate weighted sum vector
        $weightedSum = array_fill(0, $n, 0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }
        
        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] != 0) {
                $lambdaMax += $weightedSum[$i] / $weights[$i];
            }
        }
        $lambdaMax = $lambdaMax / $n;
        
        // Calculate Consistency Index
        $ci = ($lambdaMax - $n) / ($n - 1);
        
        // Calculate Consistency Ratio
        $ri = $this->randomIndex[$n] ?? 1.32;
        $cr = $ci / $ri;
        
        return $cr;
    }
    
    private function calculateMatches($kostId, $userId)
    {
        try {
            $myProfile = MatchmakingProfile::where('user_id', $userId)
                ->where('kost_id', $kostId)
                ->first();
                
            if (!$myProfile) {
                \Log::info("No profile found for user {$userId} in kost {$kostId}");
                return;
            }
            
            $otherProfiles = MatchmakingProfile::where('kost_id', $kostId)
                ->where('user_id', '!=', $userId)
                ->where('is_visible', true)
                ->get();
            
            \Log::info("Found {$otherProfiles->count()} other profiles for user {$userId} in kost {$kostId}");
            
            // Collect all candidates for TOPSIS
            $candidates = [];
            $candidateIds = [];
            
            foreach ($otherProfiles as $profile) {
                $candidates[] = $this->preferencesToVector($profile->preferences);
                $candidateIds[] = $profile->user_id;
            }
            
            if (empty($candidates)) {
                return;
            }
            
            // Apply TOPSIS algorithm
            $myWeights = $myProfile->ahp_weights ?? [0.30, 0.20, 0.15, 0.10, 0.10, 0.10, 0.05];
            $scores = $this->topsisRanking($candidates, $myWeights);
            
            // Save matches
            foreach ($scores as $index => $score) {
                $otherUserId = $candidateIds[$index];
                
                $match = UserMatch::updateOrCreate(
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
                
                \Log::info("Match saved with score {$score} between user {$userId} and user {$otherUserId}");
            }
        } catch (\Exception $e) {
            \Log::error('Calculate matches error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }
    
    /**
     * Convert preferences to numerical vector for TOPSIS
     */
    private function preferencesToVector($preferences)
    {
        return [
            $preferences['budget'],                    // C1: Budget (1-5)
            $preferences['smoke'] === 'yes' ? 1 : 0,  // C2: Smoke (0-1)
            $preferences['clean'],                     // C3: Clean (1-5)
            $preferences['sleep'] === 'early' ? 1 : 0, // C4: Sleep (0-1)
            $preferences['noise'],                     // C5: Noise (1-5)
            $this->socialToNumeric($preferences['social']), // C6: Social (1-5)
            $preferences['worship'] === 'strict' ? 1 : 0    // C7: Worship (0-1)
        ];
    }
    
    /**
     * TOPSIS Algorithm Implementation
     */
    private function topsisRanking($candidates, $weights)
    {
        $m = count($candidates);      // Number of alternatives
        $n = count($candidates[0]);   // Number of criteria
        
        // Step 1: Create decision matrix (already done - $candidates)
        
        // Step 2: Normalize decision matrix
        $normalizedMatrix = [];
        
        // Calculate column sums of squares
        $columnSumSquares = array_fill(0, $n, 0);
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $columnSumSquares[$j] += pow($candidates[$i][$j], 2);
            }
        }
        
        // Normalize each element
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $candidates[$i][$j] / sqrt($columnSumSquares[$j]);
            }
        }
        
        // Step 3: Create weighted normalized matrix
        $weightedMatrix = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedMatrix[$i][$j] = $normalizedMatrix[$i][$j] * $weights[$j];
            }
        }
        
        // Step 4: Determine ideal solutions
        $idealPositive = [];
        $idealNegative = [];
        
        for ($j = 0; $j < $n; $j++) {
            $column = array_column($weightedMatrix, $j);
            $idealPositive[$j] = max($column);  // For benefit criteria
            $idealNegative[$j] = min($column);  // For cost criteria
        }
        
        // Step 5: Calculate distances
        $distancePositive = [];
        $distanceNegative = [];
        
        for ($i = 0; $i < $m; $i++) {
            $sumPositive = 0;
            $sumNegative = 0;
            
            for ($j = 0; $j < $n; $j++) {
                $sumPositive += pow($weightedMatrix[$i][$j] - $idealPositive[$j], 2);
                $sumNegative += pow($weightedMatrix[$i][$j] - $idealNegative[$j], 2);
            }
            
            $distancePositive[$i] = sqrt($sumPositive);
            $distanceNegative[$i] = sqrt($sumNegative);
        }
        
        // Step 6: Calculate closeness coefficient
        $closenessCoefficient = [];
        for ($i = 0; $i < $m; $i++) {
            $closenessCoefficient[$i] = $distanceNegative[$i] / ($distancePositive[$i] + $distanceNegative[$i]);
        }
        
        return $closenessCoefficient;
    }
    
    private function socialToNumeric($social)
    {
        $map = ['introvert' => 1, 'ambivert' => 3, 'extrovert' => 5];
        return $map[$social] ?? 3;
    }
}