<?php

namespace Database\Seeders;

use App\Models\Affiliation;
use Illuminate\Database\Seeder;

class AffiliationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan data lama
        Affiliation::truncate();

        $affiliations = [
            [
                'name' => 'PBNU (Pengurus Besar Nahdlatul Ulama)',
                'description' => 'Pimpinan tertinggi organisasi Nahdlatul Ulama di tingkat nasional.',
                'external_url' => 'https://www.nu.or.id',
            ],
            [
                'name' => 'PWNU Jawa Timur',
                'description' => 'Pengurus Wilayah Nahdlatul Ulama Provinsi Jawa Timur.',
                'external_url' => 'https://jatim.nu.or.id',
            ],
            [
                'name' => 'LAZISNU',
                'description' => 'Lembaga Amil Zakat, Infaq, dan Shadaqah NU.',
                'external_url' => 'https://lazisnu.or.id',
            ],
            [
                'name' => 'GP Ansor',
                'description' => 'Organisasi kepemudaan di bawah Nahdlatul Ulama.',
                'external_url' => 'https://ansor.or.id',
            ],
            [
                'name' => 'Fatayat NU',
                'description' => 'Organisasi perempuan muda Nahdlatul Ulama.',
                'external_url' => 'https://fatayat.or.id',
            ],
            [
                'name' => 'Muslimat NU',
                'description' => 'Organisasi perempuan Nahdlatul Ulama.',
                'external_url' => 'https://muslimatnu.or.id',
            ],
            [
                'name' => 'IPNU',
                'description' => 'Ikatan Pelajar Nahdlatul Ulama.',
                'external_url' => 'https://ipnu.or.id',
            ],
            [
                'name' => 'IPPNU',
                'description' => 'Ikatan Pelajar Putri Nahdlatul Ulama.',
                'external_url' => 'https://ippnu.or.id',
            ],
        ];

        foreach ($affiliations as $data) {
            Affiliation::create($data);
        }

        $this->command->info('Seeder afiliasi berhasil ditambahkan!');
        $this->command->info('Total: ' . count($affiliations) . ' afiliasi');
    }
}
