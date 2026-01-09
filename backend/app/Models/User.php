<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;  // Import interface JWTSubject

class User extends Authenticatable implements JWTSubject  // Implementasi JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Mendapatkan identifier unik untuk JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan ID pengguna
    }

    /**
     * Mendapatkan klaim kustom untuk JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // Jika perlu, Anda bisa menambahkan klaim kustom di sini
    }
}
