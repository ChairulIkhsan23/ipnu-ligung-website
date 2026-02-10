<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TopKategoriBlogPostWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung statistik dasar
        $totalCategories = Category::count();
        $categoriesWithPosts = Category::has('posts')->count();
        
        // Kategori dengan artikel terbanyak
        $topCategory = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->first();

        $stats = [];
        
        // Total Kategori
        $stats[] = Stat::make('Total Kategori', $totalCategories)
            ->description('Semua kategori yang tersedia')
            ->color('primary')
            ->icon('heroicon-o-tag')
            ->chart([5, 8, 12, 15, 18, 20, $totalCategories])
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-500',
            ]);

        // Kategori dengan Artikel
        $stats[] = Stat::make('Kategori Aktif', $categoriesWithPosts)
            ->description('dari ' . $totalCategories . ' total')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->chart([1, 3, 5, 7, 9, 11, $categoriesWithPosts])
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500',
            ]);

        // Rata-rata Artikel per Kategori
        $avgPosts = $categoriesWithPosts > 0 
            ? round(Category::has('posts')->withCount('posts')->get()->avg('posts_count'), 1)
            : 0;
            
        $stats[] = Stat::make('Rata-rata Artikel', $avgPosts)
            ->description('per kategori aktif')
            ->color('warning')
            ->icon('heroicon-o-chart-bar')
            ->chart([1, 2, 3, 4, 3, 4, $avgPosts])
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-amber-50 to-orange-50 border-l-4 border-amber-500',
            ]);

        // Kategori Terpopuler
        if ($topCategory && $topCategory->posts_count > 0) {
            $stats[] = Stat::make('Kategori Terpopuler', $topCategory->name)
                ->description($topCategory->posts_count . ' artikel')
                ->color('danger')
                ->icon('heroicon-o-fire')
                ->chart([1, 3, 6, 10, 15, 21, $topCategory->posts_count])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-red-50 to-pink-50 border-l-4 border-red-500',
                ]);
        }

        return $stats;
    }

    protected function getColumns(): int
    {
        return 4;
    }
}