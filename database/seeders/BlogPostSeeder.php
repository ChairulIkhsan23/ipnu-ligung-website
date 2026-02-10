<?php
// database/seeders/BlogPostSeeder.php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user editor untuk author
        $editor = User::where('email', 'editor1@gmail.com')->first();
        
        if (!$editor) {
            // Buat user editor jika belum ada
            $editor = User::create([
                'name' => 'Editor IPNU',
                'email' => 'editor1@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('User editor berhasil dibuat: editor1@gmail.com');
        }

        // Dapatkan semua kategori
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $posts = [
            [
                'title' => 'Pelantikan Pengurus MWC NU Periode 2024-2029',
                'slug' => 'pelantikan-pengurus-mwc-nu-2024-2029',
                'excerpt' => 'MWC NU Ligung resmi melantik pengurus baru untuk periode 2024-2029 dalam acara yang dihadiri oleh berbagai elemen masyarakat.',
                'content' => $this->generateContent('Pelantikan Pengurus'),
                'category_id' => $categories->where('slug', 'berita')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Pentingnya Pendidikan Karakter dalam Islam',
                'slug' => 'pentingnya-pendidikan-karakter-dalam-islam',
                'excerpt' => 'Artikel ini membahas tentang bagaimana pendidikan karakter menjadi fondasi penting dalam ajaran Islam.',
                'content' => $this->generateContent('Pendidikan Karakter'),
                'category_id' => $categories->where('slug', 'artikel')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Jadwal Kajian Rutin Bulan Desember 2024',
                'slug' => 'jadwal-kajian-rutin-desember-2024',
                'excerpt' => 'Berikut adalah jadwal kajian rutin yang akan dilaksanakan selama bulan Desember 2024.',
                'content' => $this->generateContent('Jadwal Kajian'),
                'category_id' => $categories->where('slug', 'pengumuman')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Laporan Kegiatan Bakti Sosial di Desa Ligung',
                'slug' => 'laporan-kegiatan-bakti-sosial-desa-ligung',
                'excerpt' => 'MWC NU Ligung sukses menggelar kegiatan bakti sosial yang diikuti oleh puluhan relawan.',
                'content' => $this->generateContent('Bakti Sosial'),
                'category_id' => $categories->where('slug', 'kegiatan')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Memahami Makna Ikhlas dalam Beramal',
                'slug' => 'memahami-makna-ikhlas-dalam-beramal',
                'excerpt' => 'Konsep ikhlas seringkali disalahpahami. Artikel ini mengupas makna sebenarnya dari ikhlas.',
                'content' => $this->generateContent('Makna Ikhlas'),
                'category_id' => $categories->where('slug', 'edukasi')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Kisah Inspiratif dari Seorang Pengurus NU',
                'slug' => 'kisah-inspiratif-pengurus-nu',
                'excerpt' => 'Perjalanan hidup seorang pengurus NU yang penuh perjuangan dan keteladanan.',
                'content' => $this->generateContent('Kisah Inspiratif'),
                'category_id' => $categories->where('slug', 'inspirasi')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Workshop Digitalisasi Administrasi NU',
                'slug' => 'workshop-digitalisasi-administrasi-nu',
                'excerpt' => 'Workshop untuk meningkatkan kemampuan pengurus dalam mengelola administrasi secara digital.',
                'content' => $this->generateContent('Workshop Digital'),
                'category_id' => $categories->where('slug', 'kegiatan')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Peran Pemuda NU di Era Digital',
                'slug' => 'peran-pemuda-nu-era-digital',
                'excerpt' => 'Bagaimana pemuda NU dapat berkontribusi lebih besar di era digital saat ini.',
                'content' => $this->generateContent('Pemuda NU'),
                'category_id' => $categories->where('slug', 'artikel')->first()->id,
                'status' => 'draft',
                'published_at' => now()->addDays(5),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Silaturahmi dengan Ormas Lain se-Kecamatan Ligung',
                'slug' => 'silaturahmi-ormas-lain-kecamatan-ligung',
                'excerpt' => 'MWC NU Ligung mengadakan silaturahmi dengan berbagai ormas untuk mempererat ukhuwah.',
                'content' => $this->generateContent('Silaturahmi'),
                'category_id' => $categories->where('slug', 'kegiatan')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
            [
                'title' => 'Tata Cara Shalat Berjamaah yang Benar',
                'slug' => 'tata-cara-shalat-berjamaah-yang-benar',
                'excerpt' => 'Panduan lengkap tentang tata cara melaksanakan shalat berjamaah sesuai sunnah.',
                'content' => $this->generateContent('Shalat Berjamaah'),
                'category_id' => $categories->where('slug', 'edukasi')->first()->id,
                'status' => 'published',
                'published_at' => now()->subDays(25),
                'author_id' => $editor->id, // Gunakan editor sebagai author
            ],
        ];

        $createdCount = 0;
        foreach ($posts as $post) {
            // Cek apakah artikel sudah ada
            $existingPost = BlogPost::where('slug', $post['slug'])->first();
            
            if (!$existingPost) {
                BlogPost::create(array_merge($post, [
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                ]));
                $createdCount++;
            }
        }

        $this->command->info('Seeder artikel blog berhasil ditambahkan!');
        $this->command->info('Author: ' . $editor->name . ' (' . $editor->email . ')');
        $this->command->info('Total artikel dibuat: ' . $createdCount);
        $this->command->info('Total artikel sudah ada: ' . (count($posts) - $createdCount));
    }

    private function generateContent(string $topic): string
    {
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
        
        $contents = [
            "<h2>Pendahuluan tentang {$topic}</h2>",
            "<p>{$lorem}</p>",
            "<h3>Manfaat dan Keuntungan</h3>",
            "<p>{$lorem}</p>",
            "<h3>Langkah-langkah Pelaksanaan</h3>",
            "<p>{$lorem}</p>",
            "<ul>",
            "<li>Poin penting pertama tentang {$topic}</li>",
            "<li>Poin penting kedua tentang {$topic}</li>",
            "<li>Poin penting ketiga tentang {$topic}</li>",
            "</ul>",
            "<h3>Kesimpulan</h3>",
            "<p>{$lorem}</p>",
            "<blockquote>\"Ini adalah kutipan penting tentang {$topic}\" - Anonim</blockquote>",
        ];

        return implode('', $contents);
    }
}