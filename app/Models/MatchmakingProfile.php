<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchmakingProfile extends Model
{
    protected $fillable = ['user_id', 'kost_id', 'preferences', 'ahp_weights', 'is_visible'];

    protected $casts = [
        'preferences' => 'array',
        'ahp_weights' => 'array',
        'is_visible' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}
