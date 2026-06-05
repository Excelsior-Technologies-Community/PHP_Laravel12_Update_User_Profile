<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'city',
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
    
    // Accessor for avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(public_path('avatars/' . $this->avatar))) {
            return asset('avatars/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?background=0D6EFD&color=fff&name=' . urlencode($this->name);
    }
    
    // Check if profile is complete
    public function isProfileComplete()
    {
        return !empty($this->phone) && !empty($this->city) && !empty($this->avatar);
    }
}