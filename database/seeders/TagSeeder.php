<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data tag contoh untuk konten NU (Nahdlatul Ulama)
        $tags = [
            // Tag Pendidikan
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
            ],
            [
                'name' => 'Pesantren',
                'slug' => 'pesantren',
            ],
            [
                'name' => 'Madrasah',
                'slug' => 'madrasah',
            ],
            [
                'name' => 'Kitab Kuning',
                'slug' => 'kitab-kuning',
            ],
            
            // Tag Sosial & Kemasyarakatan
            [
                'name' => 'Sosial',
                'slug' => 'sosial',
            ],
            [
                'name' => 'Kemanusiaan',
                'slug' => 'kemanusiaan',
            ],
            [
                'name' => 'Bansos',
                'slug' => 'bansos',
            ],
            [
                'name' => 'Zakat',
                'slug' => 'zakat',
            ],
            [
                'name' => 'Wakaf',
                'slug' => 'wakaf',
            ],
            
            // Tag Keagamaan
            [
                'name' => 'Fikih',
                'slug' => 'fikih',
            ],
            [
                'name' => 'Tasawuf',
                'slug' => 'tasawuf',
            ],
            [
                'name' => 'Tauhid',
                'slug' => 'tauhid',
            ],
            [
                'name' => 'Aswaja',
                'slug' => 'aswaja',
            ],
            
            // Tag Organisasi
            [
                'name' => 'PCNU',
                'slug' => 'pcnu',
            ],
            [
                'name' => 'MWCNU',
                'slug' => 'mwccnu',
            ],
            [
                'name' => 'Banom',
                'slug' => 'banom',
            ],
            [
                'name' => 'LPNU',
                'slug' => 'lpnu',
            ],
            
            // Tag Kegiatan
            [
                'name' => 'Pengajian',
                'slug' => 'pengajian',
            ],
            [
                'name' => 'Seminar',
                'slug' => 'seminar',
            ],
            [
                'name' => 'Pelatihan',
                'slug' => 'pelatihan',
            ],
            [
                'name' => 'Baznas',
                'slug' => 'baznas',
            ],
            
            // Tag Lokasi
            [
                'name' => 'Jawa Timur',
                'slug' => 'jawa-timur',
            ],
            [
                'name' => 'Jawa Tengah',
                'slug' => 'jawa-tengah',
            ],
            [
                'name' => 'Jakarta',
                'slug' => 'jakarta',
            ],
            
            // Tag Program
            [
                'name' => 'NU Care',
                'slug' => 'nu-care',
            ],
            [
                'name' => 'LP Maarif',
                'slug' => 'lp-maarif',
            ],
            [
                'name' => 'LAZISNU',
                'slug' => 'lazisnu',
            ],
            [
                'name' => 'Bank Wakaf Mikro',
                'slug' => 'bank-wakaf-mikro',
            ],
            
            // Tag Umum
            [
                'name' => 'Berita',
                'slug' => 'berita',
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
            ],
            [
                'name' => 'Agenda',
                'slug' => 'agenda',
            ],
            [
                'name' => 'Artikel',
                'slug' => 'artikel',
            ],
        ];

        // Insert data tag ke database
        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }

        $this->command->info('Seeder Tag berhasil dijalankan!');
        $this->command->info('Total tag: ' . count($tags));
    }
}