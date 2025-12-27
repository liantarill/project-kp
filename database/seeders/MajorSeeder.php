<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Major::create([
            'name' => 'Ilmu Komputer',
            'institution_id' => 1,
            'level' => 'S1',
        ]);
    }
}
