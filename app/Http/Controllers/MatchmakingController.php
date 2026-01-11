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
    public function index()
    {
        // Check eligibility
        $user = Auth::user();
        
        // Check 1: User must be approved
        if ($user->status !== 'approved') {
            return view('user.matchmaking-locked', [
                'reason' => 'identity',
                'message' => 'Identitas Anda belum diverifikasi oleh admin. Silakan tunggu proses verifikasi.'
            ]);
        }
        
        // Check 2: User must have approved rental application
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
        
        // User is eligible - show kost selection or matchmaking
        return view('user.matchmaking', [
            'approvedKosts' => $approvedKosts
        ]);
    }
    
    public function selectKost($kostId)
    {
        $user = Auth::user();
        
        // Verify user has approved application for this kost
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
        
        MatchmakingProfile::updateOrCreate(
            ['user_id' => Auth::id(), 'kost_id' => $validated['kost_id']],
            ['preferences' => $preferences]
        );
        
        // Calculate matches
        $this->calculateMatches($validated['kost_id'], Auth::id());
        
        return response()->json(['success' => true]);
    }
    
    public function results($kostId)
    {
        $user = Auth::user();
        
        // Get user's profile
        $myProfile = MatchmakingProfile::where('user_id', $user->id)
            ->where('kost_id', $kostId)
            ->first();
            
        if (!$myProfile) {
            return redirect()->route('matchmaking.select', $kostId);
        }
        
        // Get matches from database
        $matches = UserMatch::where('kost_id', $kostId)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->where('status', 'active')
            ->with(['user1', 'user2'])
            ->orderBy('compatibility_score', 'desc')
            ->get();
            
        $kost = Kost::findOrFail($kostId);
        
        return view('user.matchmaking-results', [
            'matches' => $matches,
            'kost' => $kost
        ]);
    }
    
    private function calculateMatches($kostId, $userId)
    {
        $myProfile = MatchmakingProfile::where('user_id', $userId)
            ->where('kost_id', $kostId)
            ->first();
            
        if (!$myProfile) return;
        
        // Get other profiles in same kost
        $otherProfiles = MatchmakingProfile::where('kost_id', $kostId)
            ->where('user_id', '!=', $userId)
            ->get();
            
        foreach ($otherProfiles as $profile) {
            $score = $this->calculateCompatibility($myProfile->preferences, $profile->preferences);
            
            // Store or update match
            UserMatch::updateOrCreate(
                [
                    'user1_id' => min($userId, $profile->user_id),
                    'user2_id' => max($userId, $profile->user_id),
                    'kost_id' => $kostId
                ],
                [
                    'compatibility_score' => $score,
                    'status' => 'active'
                ]
            );
        }
    }
    
    private function calculateCompatibility($pref1, $pref2)
    {
        $score = 100;
        
        // Smoking is dealbreaker (30 points)
        if ($pref1['smoke'] !== $pref2['smoke']) {
            $score -= 30;
        }
        
        // Sleep pattern (15 points)
        if ($pref1['sleep'] !== $pref2['sleep']) {
            $score -= 15;
        }
        
        // Budget difference (5 points per level)
        $score -= abs($pref1['budget'] - $pref2['budget']) * 5;
        
        // Cleanliness difference (5 points per level)
        $score -= abs($pref1['clean'] - $pref2['clean']) * 5;
        
        // Noise tolerance (5 points per level)
        $score -= abs($pref1['noise'] - $pref2['noise']) * 5;
        
        // Social compatibility (10 points)
        if ($pref1['social'] !== $pref2['social']) {
            $score -= 10;
        }
        
        // Worship compatibility (10 points)
        if ($pref1['worship'] !== $pref2['worship']) {
            $score -= 10;
        }
        
        return max(40, min(99, $score));
    }
}
