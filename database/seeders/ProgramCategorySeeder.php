<?php

namespace Database\Seeders;

use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kategori program untuk organisasi NU
        $categories = [
            // Kategori Sosial & Kemanusiaan
            [
                'name' => 'Sosial & Kemanusiaan',
                'slug' => 'sosial-kemanusiaan',
                'description' => 'Program-program yang berfokus pada bantuan sosial, kemanusiaan, dan pemberdayaan masyarakat kurang mampu.',
            ],
            [
                'name' => 'Bantuan Sosial',
                'slug' => 'bantuan-sosial',
                'description' => 'Program bantuan langsung tunai, sembako, dan kebutuhan pokok untuk masyarakat terdampak.',
            ],
            [
                'name' => 'Santunan Anak Yatim',
                'slug' => 'santunan-anak-yatim',
                'description' => 'Program rutin pemberian santunan, pendidikan, dan pendampingan untuk anak yatim dan dhuafa.',
            ],
            
            // Kategori Pendidikan
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Program pengembangan pendidikan formal dan non-formal, beasiswa, dan pelatihan.',
            ],
            [
                'name' => 'Beasiswa',
                'slug' => 'beasiswa',
                'description' => 'Program pemberian beasiswa untuk siswa dan mahasiswa berprestasi dari keluarga kurang mampu.',
            ],
            [
                'name' => 'Pesantren & Madrasah',
                'slug' => 'pesantren-madrasah',
                'description' => 'Pengembangan dan pembinaan lembaga pendidikan pesantren dan madrasah.',
            ],
            
            // Kategori Kesehatan
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'description' => 'Program layanan kesehatan, pengobatan gratis, dan penyuluhan kesehatan masyarakat.',
            ],
            [
                'name' => 'Pengobatan Gratis',
                'slug' => 'pengobatan-gratis',
                'description' => 'Layanan kesehatan dan pengobatan gratis untuk masyarakat tidak mampu.',
            ],
            [
                'name' => 'Bantuan Medis',
                'slug' => 'bantuan-medis',
                'description' => 'Distribusi alat kesehatan dan obat-obatan untuk puskesmas dan klinik.',
            ],
            
            // Kategori Ekonomi & Kewirausahaan
            [
                'name' => 'Ekonomi & Kewirausahaan',
                'slug' => 'ekonomi-kewirausahaan',
                'description' => 'Program pemberdayaan ekonomi, pelatihan kewirausahaan, dan modal usaha.',
            ],
            [
                'name' => 'UMKM',
                'slug' => 'umkm',
                'description' => 'Pendampingan dan pembiayaan Usaha Mikro, Kecil, dan Menengah.',
            ],
            [
                'name' => 'Pelatihan Kerja',
                'slug' => 'pelatihan-kerja',
                'description' => 'Program pelatihan keterampilan kerja dan sertifikasi profesi.',
            ],
            
            // Kategori Keagamaan
            [
                'name' => 'Keagamaan',
                'slug' => 'keagamaan',
                'description' => 'Program pengembangan keagamaan, pengajian, dan pembinaan masyarakat.',
            ],
            [
                'name' => 'Pengajian & Majelis Taklim',
                'slug' => 'pengajian-majelis-taklim',
                'description' => 'Pembinaan dan fasilitasi kegiatan pengajian rutin dan majelis taklim.',
            ],
            [
                'name' => 'Ramadhan & Qurban',
                'slug' => 'ramadhan-qurban',
                'description' => 'Program khusus bulan Ramadhan dan penyelenggaraan ibadah qurban.',
            ],
            
            // Kategori Lingkungan
            [
                'name' => 'Lingkungan',
                'slug' => 'lingkungan',
                'description' => 'Program pelestarian lingkungan, penghijauan, dan pengelolaan sampah.',
            ],
            [
                'name' => 'Penghijauan',
                'slug' => 'penghijauan',
                'description' => 'Program penanaman pohon dan pelestarian lingkungan hidup.',
            ],
            
            // Kategori Bencana
            [
                'name' => 'Tanggap Bencana',
                'slug' => 'tanggap-bencana',
                'description' => 'Program emergency response dan rehabilitasi pasca bencana alam.',
            ],
            
            // Kategori Zakat & Wakaf
            [
                'name' => 'Zakat',
                'slug' => 'zakat',
                'description' => 'Program pengelolaan dan penyaluran zakat kepada mustahik.',
            ],
            [
                'name' => 'Wakaf',
                'slug' => 'wakaf',
                'description' => 'Pengelolaan dan pengembangan aset wakaf produktif.',
            ],
            
            // Kategori Pemberdayaan Perempuan
            [
                'name' => 'Pemberdayaan Perempuan',
                'slug' => 'pemberdayaan-perempuan',
                'description' => 'Program khusus untuk pemberdayaan perempuan dan anak.',
            ],
            
            // Kategori Pemuda & Olahraga
            [
                'name' => 'Pemuda & Olahraga',
                'slug' => 'pemuda-olahraga',
                'description' => 'Program pembinaan pemuda dan pengembangan olahraga masyarakat.',
            ],
        ];

        // Insert data kategori program ke database
        foreach ($categories as $category) {
            ProgramCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Seeder ProgramCategory berhasil dijalankan!');
        $this->command->info('Total kategori program: ' . count($categories));
        
        // Tampilkan preview data
        $this->command->table(
            ['Nama Kategori', 'Slug', 'Deskripsi'],
            array_map(function($cat) {
                return [
                    $cat['name'],
                    $cat['slug'],
                    Str::limit($cat['description'], 50),
                ];
            }, array_slice($categories, 0, 5))
        );
    }
}