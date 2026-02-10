<?php

namespace App\Filament\Resources\TagResource\Widgets;

use App\Models\Tag;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TopTagsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $topTags = Tag::withCount(['posts', 'programs'])
            ->orderByRaw('(posts_count + programs_count) DESC')
            ->limit(3)
            ->get();

        $stats = [];
        
        // Stat utama dengan gradient dan icon menarik
        $stats[] = Stat::make('Total Tag', Tag::count())
            ->description('Semua tag yang tersedia')
            ->color('primary')
            ->icon('heroicon-o-sparkles')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-500',
            ]);

        if ($topTags->isNotEmpty()) {
            $topTag = $topTags->first();
            $totalUsage = $topTag->posts_count + $topTag->programs_count;
            
            $stats[] = Stat::make('Tag Terpopuler', $topTag->name)
                ->description($totalUsage . ' total penggunaan')
                ->color('success')
                ->icon('heroicon-o-fire')
                ->chart([1, 5, 8, 12, 15, 18, 22])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500',
                ]);

            // Rata-rata penggunaan
            $avgUsage = $topTags->avg(function($tag) {
                return $tag->posts_count + $tag->programs_count;
            });
            
            $stats[] = Stat::make('Rata-rata Penggunaan', round($avgUsage, 1))
                ->description('Per tag')
                ->color('warning')
                ->icon('heroicon-o-chart-bar')
                ->chart([3, 5, 4, 6, 5, 7, 6])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-amber-50 to-orange-50 border-l-4 border-amber-500',
                ]);
        }

        return $stats;
    }

    protected function getColumns(): int
    {
        return 3;
    }
}