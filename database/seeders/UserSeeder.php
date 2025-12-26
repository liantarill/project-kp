<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'id' => Str::uuid(),
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // PARTICIPANT (PENDING)
        User::create([
            'id' => Str::uuid(),
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'username' => 'budi',
            'role' => 'participant',
            'status' => 'pending',
            'password' => Hash::make('password'),
        ]);

        // PARTICIPANT (ACTIVE)
        User::create([
            'id' => Str::uuid(),
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'username' => 'siti',
            'role' => 'participant',
            'status' => 'active',
            'start_date' => now()->subWeek(),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
