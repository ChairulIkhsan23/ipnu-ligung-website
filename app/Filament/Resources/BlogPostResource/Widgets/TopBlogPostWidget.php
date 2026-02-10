<?php

namespace App\Filament\Resources\BlogPostResource\Widgets;

use App\Models\BlogPost;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TopBlogPostWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalArtikel = BlogPost::count();
        $artikelPublik = BlogPost::where('is_published', true)->count();
        $artikelDraft = BlogPost::where('is_published', false)->count();
        $artikelBulanIni = BlogPost::whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Total Artikel', $totalArtikel)
                ->description('Semua artikel')
                ->color('primary')
                ->icon('heroicon-o-newspaper'),

            Stat::make('Dipublikasi', $artikelPublik)
                ->description($totalArtikel > 0 ? round(($artikelPublik / $totalArtikel) * 100, 1) . '% dari total' : '0%')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Draft', $artikelDraft)
                ->description($totalArtikel > 0 ? round(($artikelDraft / $totalArtikel) * 100, 1) . '% dari total' : '0%')
                ->color('gray')
                ->icon('heroicon-o-pencil'),

            Stat::make('Bulan Ini', $artikelBulanIni)
                ->description('Artikel dibuat bulan ini')
                ->color('warning')
                ->icon('heroicon-o-calendar'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}