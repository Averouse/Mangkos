<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    protected $fillable = ['user_id', 'kost_id', 'status', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }
}
