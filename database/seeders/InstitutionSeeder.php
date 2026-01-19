<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            'SMK N 4 Bandar Lampung',
            'SMK N 8 Bandar Lampung',
            'SMK N 9 Bandar Lampung',
            'SMKN 1 Bandar Lampung',
            'SMKN 2 Bandar Lampung',
            'SMKN 3 Bandar Lampung',
            'SMKN 5 Bandar Lampung',
            'SMKN 6 Bandar Lampung',
            'SMKN 7 Bandar Lampung',
            'SMK SMTI Bandar Lampung',

            'SMKS Taruna Bandar Lampung',
            'SMKS Yapena Bandar Lampung',
            'SMK S PGRI 1 Bandar Lampung',
            'SMKS YPPL Bandar Lampung',
            'SMKS Satu Nusa 1 Bandar Lampung',
            'SMK Kesehatan Bandar Lampung',
            'SMKS Surya Dharma Bandar Lampung',
            'SMK P Raden Intan',
            'SMKS Kridawisata Bandar Lampung',
            'SMKS Miftahul Ulum',
        ];



        $univ = [
            'Universitas Lampung',
            'Institut Teknologi Sumatera',
            'Universitas Teknokrat Indonesia',
            'Universitas Islam Negeri Raden Intan Lampung',
            'Universitas Bandar Lampung',
            'Universitas Malahayati',
            'Universitas Muhammadiyah Metro',
            "Universitas Muhammadiyah Pringsewu",
            'Universitas Muhammadiyah Lampung',
            'Universitas Tulang Bawang',
            'Universitas Nahdlatul Ulama Lampung',
            'Politeknik Negeri Lampung',
            'Politeknik Kesehatan Tanjungkarang',




            'Universitas Indonesia',
            'Universitas Gadjah Mada',
            'Institut Teknologi Bandung',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Airlangga',
            'Universitas Diponegoro',
            'Universitas Padjadjaran',
            'Universitas Brawijaya',
            'Universitas Sebelas Maret',
            'Universitas Hasanuddin',
            'Universitas Andalas',
            'Universitas Sriwijaya',
            'Universitas Sumatera Utara',
            'Universitas Pendidikan Indonesia',
            'Universitas Negeri Yogyakarta',
        ];

        foreach ($schools as $name) {
            Institution::create([
                'name' => $name,
                'type' => 'SMK',
            ]);
        }
        foreach ($univ as $name) {
            Institution::create([
                'name' => $name,
                'type' => 'PERGURUAN_TINGGI',
            ]);
        }
    }
}
