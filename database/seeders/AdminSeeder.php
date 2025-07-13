<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    // database/seeders/AdminSeeder.php

public function run(): void
{
    User::firstOrCreate(
        // The first array tells Eloquent how to find the user.
        // We'll look for them by the new email address.
        ['email' => 'brandraize1@gmail.com'], // ✅ CHANGED: Your new email address

        // The second array contains the data to create if the user is not found.
        [
            'name' => 'Admin',
            'mobile' => '0500000000',
            'role' => 'admin',
            'password' => Hash::make('admin'), // ✅ CHANGED: Your new password
        ]
    );
}
}