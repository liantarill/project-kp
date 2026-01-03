<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Sumber Daya Manusia (SDM)',
            'quota' => 4,
        ]);
        Department::create([
            'name' => 'Akuntansi dan Keuangan (ANK)',
            'quota' => 4,
        ]);

        Department::create([
            'name' => 'Sekretariat dan Hukum (SKR)',
            'quota' => 4,
        ]);
        Department::create([
            'name' => 'Tanaman dan Teknik Pengolahan',
            'quota' => 4,
        ]);
        Department::create([
            'name' => 'Manajemen Aset dan Pemasaran',
            'quota' => 4,
        ]);
        Department::create([
            'name' => 'Kepatuhan dan Manajemen Risiko',
            'quota' => 4,
        ]);
        Department::create([
            'name' => 'Project Management Office (PMO)',
            'quota' => 4,
        ]);
    }
}
