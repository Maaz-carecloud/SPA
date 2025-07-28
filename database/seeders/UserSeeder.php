<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'username' => 'super-admin',
            'user_type' => 'admin',
            'password' => Hash::make('password'), // Use a secure password
        ]);

        $user->assignRole('Super Admin');
    }
}
