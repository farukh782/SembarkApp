<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','company_id'];

     protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class, 'user_id');
    }

     public function invitationsSent(): HasMany
    {
        return $this->hasMany(Invitation::class, 'inviter_id');
    }

    public function isRole(string $role): bool
    {
        return strtolower($this->role) === strtolower($role);
    }

    public function isAnyRole(array $roles): bool
    {
        return in_array(strtolower($this->role), array_map('strtolower', $roles));
    }
}
