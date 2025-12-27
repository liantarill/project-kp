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
            'role' => 'participant',

            'institution_id' => 1,
            'major' => 'Sistem Informasi',
            'level' => 'S1',
            'department_id' => 1,

            'status' => 'active',
            'start_date' => now()->subDays(7),
            'end_date' => now()->addMonths(3),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // PARTICIPANT (ACTIVE)
        User::create([
            'id' => Str::uuid(),
            'name' => 'Siti Aisyah',
            'email' => 'siti@example.com',
            'role' => 'participant',

            'institution_id' => 1,
            'major' => 'Akuntansi',
            'level' => 'D3',
            'department_id' => 2,

            'status' => 'pending',
            'password' => Hash::make('password'),
        ]);
    }
}
