<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProgramCategory::all();
        $tags = Tag::all();

        if ($categories->isEmpty()) {
            $this->command->error('Jalankan ProgramCategorySeeder terlebih dahulu!');
            return;
        }

        $programs = [

            // 1. Sosial & Kemanusiaan
            [
                'title' => 'Baksos Ramadhan NU Peduli Dhuafa',
                'slug' => 'baksos-ramadhan-nu-peduli-dhuafa',
                'short_description' => 'Distribusi sembako dan santunan bagi keluarga dhuafa.',
                'description' => '<p>Program bantuan sosial NU untuk masyarakat dhuafa selama bulan Ramadhan.</p>',
                'category_id' => $categories->where('slug', 'sosial-kemanusiaan')->first()->id,
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(30),
                'status' => 'upcoming',
                'is_featured' => true,
                'visibility' => 'public',
                'person_in_charge' => 'Dr. H. Ahmad Shodiq',
                'meta_title' => 'Baksos Ramadhan NU',
                'meta_description' => 'Program bantuan sosial NU untuk dhuafa',
            ],

            // 2. Pendidikan
            [
                'title' => 'Beasiswa Cendekia NU',
                'slug' => 'beasiswa-cendekia-nu',
                'short_description' => 'Beasiswa bagi mahasiswa berprestasi dari keluarga kurang mampu.',
                'description' => '<p>Program beasiswa NU untuk mendukung pendidikan tinggi generasi muda.</p>',
                'category_id' => $categories->where('slug', 'beasiswa')->first()->id,
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(2),
                'status' => 'upcoming',
                'is_featured' => true,
                'visibility' => 'public',
                'person_in_charge' => 'Prof. Dr. Abdul Malik',
                'meta_title' => 'Beasiswa Cendekia NU',
                'meta_description' => 'Program beasiswa pendidikan NU',
            ],

            // 3. Kesehatan
            [
                'title' => 'Pengobatan Gratis NU Sehat',
                'slug' => 'pengobatan-gratis-nu-sehat',
                'short_description' => 'Layanan kesehatan gratis bagi masyarakat kurang mampu.',
                'description' => '<p>Program layanan kesehatan gratis NU di berbagai daerah.</p>',
                'category_id' => $categories->where('slug', 'pengobatan-gratis')->first()->id,
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(7),
                'status' => 'upcoming',
                'is_featured' => false,
                'visibility' => 'public',
                'person_in_charge' => 'dr. Ahmad Zainuri',
                'meta_title' => 'Pengobatan Gratis NU',
                'meta_description' => 'Layanan kesehatan gratis NU',
            ],

            // 4. Ekonomi
            [
                'title' => 'Pelatihan Wirausaha Santri',
                'slug' => 'pelatihan-wirausaha-santri',
                'short_description' => 'Pelatihan kewirausahaan untuk santri pasca pesantren.',
                'description' => '<p>Program pelatihan usaha dan pendampingan santri NU.</p>',
                'category_id' => $categories->where('slug', 'pelatihan-kerja')->first()->id,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonth(),
                'status' => 'ongoing',
                'is_featured' => true,
                'visibility' => 'public',
                'person_in_charge' => 'H. Faisol Rahman',
                'meta_title' => 'Pelatihan Wirausaha Santri NU',
                'meta_description' => 'Pelatihan kewirausahaan santri NU',
            ],

            // 5. Keagamaan
            [
                'title' => 'Ngaji Kitab Kuning Ramadhan',
                'slug' => 'ngaji-kitab-kuning-ramadhan',
                'short_description' => 'Kajian kitab kuning selama bulan Ramadhan.',
                'description' => '<p>Kajian kitab kuning bersama ulama NU secara rutin.</p>',
                'category_id' => $categories->where('slug', 'pengajian-majelis-taklim')->first()->id,
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(50),
                'status' => 'upcoming',
                'is_featured' => false,
                'visibility' => 'public',
                'person_in_charge' => 'KH. Muhammad Alwi',
                'meta_title' => 'Ngaji Kitab Kuning NU',
                'meta_description' => 'Kajian kitab kuning Ramadhan NU',
            ],
        ];

        foreach ($programs as $programData) {
            $program = Program::firstOrCreate(
                ['slug' => $programData['slug']],
                $programData
            );

            if ($tags->isNotEmpty()) {
                $program->tags()->sync(
                    $tags->random(min(3, $tags->count()))->pluck('id')->toArray()
                );
            }
        }

        $this->command->info('✅ Seeder Program selesai (5 program dibuat)');
    }
}
