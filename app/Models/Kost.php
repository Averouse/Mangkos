<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    protected $fillable = [
        'owner_id',
        'name', 
        'address',
        'price',
        'type',
        'total_rooms',
        'available_rooms',
        'status'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
