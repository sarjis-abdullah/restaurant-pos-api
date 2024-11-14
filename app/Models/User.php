<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'phone_verified',
        'email_verified',
        'company_id',
        'branch_id',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'order_by');
    }
    function prepared_orders(): HasMany
    {
        return $this->hasMany(Order::class, 'prepare_by');
    }
    function taken_orders(): HasMany
    {
        return $this->hasMany(Order::class, 'taken_by');
    }
    function received_orders(): HasMany
    {
        return $this->hasMany(Order::class, 'received_by');
    }
    function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'created_by', 'id');
    }
}
