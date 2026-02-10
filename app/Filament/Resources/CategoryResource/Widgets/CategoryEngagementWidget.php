<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CategoryEngagementWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung data dasar
        $totalKategori = Category::count();
        $kategoriAktif = Category::has('posts')->count();
        $totalArtikel = Category::withCount('posts')->get()->sum('posts_count');
        
        // Hitung persentase dan skor
        $persentaseAktif = $totalKategori > 0 
            ? round(($kategoriAktif / $totalKategori) * 100, 1) 
            : 0;
            
        $kepadatanArtikel = $kategoriAktif > 0
            ? round(($totalArtikel / $kategoriAktif), 1)
            : 0;

        return [
            // Statistik 1: Persentase Kategori Aktif
            Stat::make('Kategori Aktif', $persentaseAktif . '%')
                ->description('Dari total ' . $totalKategori . ' kategori')
                ->color($persentaseAktif >= 70 ? 'success' : ($persentaseAktif >= 40 ? 'warning' : 'danger'))
                ->icon('heroicon-o-chart-bar'),

            // Statistik 2: Kepadatan Artikel
            Stat::make('Kepadatan Artikel', $kepadatanArtikel)
                ->description('Rata-rata artikel per kategori')
                ->color($kepadatanArtikel >= 10 ? 'danger' : ($kepadatanArtikel >= 5 ? 'warning' : 'success'))
                ->icon('heroicon-o-document-text'),

            // Statistik 3: Tingkat Penggunaan
            Stat::make('Tingkat Penggunaan', $this->hitungTingkatPenggunaan() . '%')
                ->description('Kategori yang dimanfaatkan')
                ->color('primary')
                ->icon('heroicon-o-check-circle'),

            // Statistik 4: Efisiensi
            Stat::make('Efisiensi', $this->hitungEfisiensi() . '/10')
                ->description('Skor pemanfaatan kategori')
                ->color('warning')
                ->icon('heroicon-o-star'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }

    /**
     * Hitung tingkat penggunaan kategori
     */
    private function hitungTingkatPenggunaan(): float
    {
        $totalKategori = Category::count();
        $kategoriAktif = Category::has('posts')->count();
        
        if ($totalKategori === 0) return 0;
        
        return round(($kategoriAktif / $totalKategori) * 100, 1);
    }

    /**
     * Hitung skor efisiensi kategori (1-10)
     */
    private function hitungEfisiensi(): int
    {
        $totalKategori = Category::count();
        $kategoriAktif = Category::has('posts')->count();
        
        if ($totalKategori === 0) return 0;
        
        // Skor berdasarkan persentase kategori aktif
        $persentaseAktif = ($kategoriAktif / $totalKategori) * 100;
        
        // Konversi persentase ke skala 1-10
        return match(true) {
            $persentaseAktif >= 90 => 10,
            $persentaseAktif >= 80 => 9,
            $persentaseAktif >= 70 => 8,
            $persentaseAktif >= 60 => 7,
            $persentaseAktif >= 50 => 6,
            $persentaseAktif >= 40 => 5,
            $persentaseAktif >= 30 => 4,
            $persentaseAktif >= 20 => 3,
            $persentaseAktif >= 10 => 2,
            $persentaseAktif > 0   => 1,
            default => 0,
        };
    }
}