<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use App\Models\BlogPost;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Str;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats = [];

        // Program berlangsung
        $ongoingPrograms = Program::where('status', 'ongoing')->count();

        $stats[] = Stat::make('Program Berlangsung', $ongoingPrograms)
            ->description('Sedang berjalan')
            ->icon('heroicon-o-play-circle')
            ->color('success')
            ->url(
                route('filament.admin.resources.programs.index', [
                    'tableFilters' => [
                        'status' => ['value' => 'ongoing'],
                    ],
                ])
            );

        // Artikel terpopuler
        $topArticle = BlogPost::query()
            ->where('is_published', true)
            ->orderByDesc('views')
            ->first();

        if ($topArticle) {
            $stats[] = Stat::make(
                    'Artikel Terpopuler',
                    Str::limit($topArticle->title, 35)
                )
                ->description(number_format($topArticle->views) . ' views')
                ->color('danger')
                ->icon('heroicon-o-fire')
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-red-50 to-pink-50 border-l-4 border-red-500',
                ])
                ->url(
                    route('filament.admin.resources.blog-posts.edit', $topArticle)
                );
        }

        // Total views artikel
        $totalViews = BlogPost::sum('views') ?? 0;

        $stats[] = Stat::make('Total Views Artikel', number_format($totalViews))
            ->description('Total pembaca')
            ->icon('heroicon-o-eye')
            ->color('info')
            ->url(route('filament.admin.resources.blog-posts.index'));

        return $stats;
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
