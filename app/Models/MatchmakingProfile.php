<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchmakingProfile extends Model
{
    protected $fillable = ['user_id', 'kost_id', 'preferences'];

    protected $casts = [
        'preferences' => 'array'
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
