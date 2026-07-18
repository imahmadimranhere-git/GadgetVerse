<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_blocked',
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

    // Is user ke saare orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Is user ke saare addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Is user ka cart
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    // Is user ki wishlist
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Is user ke reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}