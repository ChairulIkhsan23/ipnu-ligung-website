<?php

namespace App\Filament\Resources\BlogPostResource\Widgets;

use App\Models\BlogPost;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArticlePerformanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $artikelTerbaru = BlogPost::latest()->first();

        return [
            // 1. Artikel Terbaru
            Stat::make(
                'Artikel Terbaru',
                $artikelTerbaru ? date('d M Y', strtotime($artikelTerbaru->created_at)) : '-'
            )
                ->description($artikelTerbaru ? $this->truncateText($artikelTerbaru->title, 50) : 'Belum ada')
                ->color('primary')
                ->icon('heroicon-o-clock'),

            // 2. Rata-Rata per Bulan
            Stat::make(
                'Rata-rata per Bulan',
                $this->hitungRataRataPerBulan() . ' artikel'
            )
                ->description('Dalam 6 bulan terakhir')
                ->color('info')
                ->icon('heroicon-o-chart-bar'),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }

    private function hitungRataRataPerBulan(): float
    {
        $enamBulanLalu = now()->subMonths(6);
        $totalArtikel = BlogPost::where('created_at', '>=', $enamBulanLalu)->count();
        
        return $totalArtikel > 0 ? round($totalArtikel / 6, 1) : 0;
    }

    private function truncateText(string $text, int $length): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}