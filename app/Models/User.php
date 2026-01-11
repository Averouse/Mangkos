<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

protected $fillable = [
    'name', 'email', 'password', 'role', 'status', 'phone', 'campus', 'major', 'year', 
    'photo', 'ktp_photo', 'id_card_photo', 'selfie_with_id_photo'
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kosts()
    {
        return $this->hasMany(Kost::class, 'owner_id');
    }
    
    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class);
    }
    
    public function matchmakingProfiles()
    {
        return $this->hasMany(MatchmakingProfile::class);
    }
    
    public function matchesAsUser1()
    {
        return $this->hasMany(UserMatch::class, 'user1_id');
    }
    
    public function matchesAsUser2()
    {
        return $this->hasMany(UserMatch::class, 'user2_id');
    }
}
