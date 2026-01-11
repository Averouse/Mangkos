<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMatch extends Model
{
    protected $table = 'matches';
    
    protected $fillable = ['user1_id', 'user2_id', 'kost_id', 'compatibility_score', 'status'];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}
