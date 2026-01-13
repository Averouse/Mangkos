<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MatchmakingProfile;
use App\Http\Controllers\MatchmakingController;

class RecalculateMatches extends Command
{
    protected $signature = 'matches:recalculate';
    protected $description = 'Recalculate all matchmaking matches';

    public function handle()
    {
        $this->info('Recalculating matches...');
        
        $profiles = MatchmakingProfile::all();
        $controller = new MatchmakingController();
        
        foreach ($profiles as $profile) {
            $this->info("Processing user {$profile->user_id} in kost {$profile->kost_id}");
            
            // Use reflection to call private method
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('calculateMatches');
            $method->setAccessible(true);
            $method->invoke($controller, $profile->kost_id, $profile->user_id);
        }
        
        $this->info('Done!');
    }
}
