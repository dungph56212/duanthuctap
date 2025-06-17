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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'is_admin',
        'is_active',
        'avatar',
        'role',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_admin' => 'boolean',
        ];
    }

    // Relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relationship with cart items
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // Relationship with reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relationship with wishlists
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Relationship with addresses
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Get shipping addresses
    public function shippingAddresses()
    {
        return $this->addresses()->where('type', 'shipping');
    }

    // Get billing addresses
    public function billingAddresses()
    {
        return $this->addresses()->where('type', 'billing');
    }

    // Get default shipping address
    public function defaultShippingAddress()
    {
        return $this->shippingAddresses()->where('is_default', true)->first();
    }

    // Get default billing address
    public function defaultBillingAddress()
    {
        return $this->billingAddresses()->where('is_default', true)->first();
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->is_admin;
    }
}
