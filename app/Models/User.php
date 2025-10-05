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
        'full_name',
        'email',
        'password',
        'role',
        'bio',
        'website',
        'linkedin',
        'instagram',
        'address',
        'profile_photo',
        'no_telp',
    ];
    public function ordersAsCustomer()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // relasi untuk seller
    public function ordersAsSeller()
    {
        return $this->hasManyThrough(Order::class, Service::class, 'user_id', 'service_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
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
        ];
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function providerApplications()
    {
        return $this->hasMany(ProviderApplication::class);
    }
    public function favoriteServices()
    {
        return $this->belongsToMany(Service::class, 'favorites')->withTimestamps();
    }
    public function bankAccounts()
    {
        return $this->hasMany(UserBankAccount::class);
    }
}
