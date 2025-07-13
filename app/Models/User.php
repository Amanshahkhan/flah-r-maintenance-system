<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log; // ✅ Import the Log facade

class User extends Authenticatable
{
    use HasFactory, Notifiable;

  
   protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'role',
        'contract_id',
        'region',
        'address',
        'email_verified_at', // ✅ ADD THIS LINE

];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ✅ ADD THIS METHOD TO HOOK INTO THE 'created' EVENT
     *
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            // We only want to run this logic for new 'client' roles.
            // This check ensures it doesn't run for the admin or other roles.
            if ($user->role === 'client') {
                
                // Eager load the contract relationship to ensure it's available
                $user->load('contract');

                // Check if the client was successfully associated with a contract
                if ($user->contract) {
                    $contractNumber = $user->contract->contract_number;

                    // =============================================================
                    // 🚀 YOUR CUSTOM LOGIC GOES HERE 🚀
                    // You now have the $user object and the $contractNumber.
                    // =============================================================

                    // --- EXAMPLE 1: Log the information for debugging ---
                    Log::info("New client created: {$user->name} ({$user->email}) has been linked to contract number: {$contractNumber}");

                    // --- EXAMPLE 2: Send a welcome notification ---
                    // (Assuming you have a WelcomeNotification class)
                    // $user->notify(new WelcomeNotification($contractNumber));

                    // --- EXAMPLE 3: Dispatch a job for heavy processing ---
                    // (Assuming you have a SetupClientAccount job)
                    // SetupClientAccount::dispatch($user, $contractNumber);
                    
                    // --- EXAMPLE 4: Update another system via an API call ---
                    // Http::post('https://other.system/api/new-client', [
                    //     'email' => $user->email,
                    //     'contract_id' => $contractNumber,
                    // ]);
                } else {
                    // This is a safety check in case a client is created without a contract_id
                    Log::warning("Client created without a contract: {$user->email}");
                }
            }
        });
    }

    /**
     * Get the contract that the user belongs to.
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'user_id');
    }
}