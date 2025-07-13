<?php

namespace App\Models;

// ✅ CHANGE THESE USE STATEMENTS
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ✅ CHANGE "extends Model" to "extends Authenticatable"
class Representative extends Authenticatable
{
    use HasFactory, Notifiable; // ✅ ADD Notifiable

    // Your existing $fillable, $hidden, and relationships are all fine.
    // Make sure 'password' is in the $fillable array.
    protected $fillable = [
        'name',
        'email',
        'phone',
        'region',
        'password', // Ensure this is here
        'address',
        'skills',
        'notes',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    // You can add relationships here if needed later
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}