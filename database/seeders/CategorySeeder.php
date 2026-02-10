<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Berita',
                'slug' => 'berita',
                'description' => 'Berita terbaru seputar organisasi dan kegiatan',
            ],
            [
                'name' => 'Artikel',
                'slug' => 'artikel',
                'description' => 'Artikel opini, edukasi, dan inspirasi',
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi dari organisasi',
            ],
            [
                'name' => 'Kegiatan',
                'slug' => 'kegiatan',
                'description' => 'Laporan kegiatan dan event',
            ],
            [
                'name' => 'Edukasi',
                'slug' => 'edukasi',
                'description' => 'Materi pembelajaran dan edukasi',
            ],
            [
                'name' => 'Inspirasi',
                'slug' => 'inspirasi',
                'description' => 'Kisah inspiratif dan motivasi',
            ],
        ];

        foreach ($categories as $category) {
            // Hanya insert field yang ada di database
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }

        $this->command->info('Seeder kategori berhasil ditambahkan!');
        $this->command->info('Total: ' . count($categories) . ' kategori');
    }
}