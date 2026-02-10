<?php

namespace App\Filament\Resources\ProgramCategoryResource\Widgets;

use App\Models\ProgramCategory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TopProgramCategoryWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];
        
        // Total kategori
        $totalCategories = ProgramCategory::count();
        $stats[] = Stat::make('Total Kategori', $totalCategories)
            ->description('Semua kategori program')
            ->color('primary')
            ->icon('heroicon-o-folder')
            ->chart([5, 8, 12, 15, 18, 20, 22])
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-500',
            ]);

        // Kategori dengan program terbanyak
        $topCategory = ProgramCategory::withCount('programs')
            ->orderBy('programs_count', 'desc')
            ->first();

        if ($topCategory && $topCategory->programs_count > 0) {
            $stats[] = Stat::make('Kategori Teraktif', $topCategory->name)
                ->description($topCategory->programs_count . ' program')
                ->color('success')
                ->icon('heroicon-o-chart-bar')
                ->chart([1, 3, 5, 8, 12, 15, 18])
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500',
                ]);
        } else {
            $stats[] = Stat::make('Program Aktif', '0')
                ->description('Belum ada program')
                ->color('gray')
                ->icon('heroicon-o-clock')
                ->extraAttributes([
                    'class' => 'bg-gradient-to-br from-gray-50 to-gray-100 border-l-4 border-gray-500',
                ]);
        }

        // Rata-rata program per kategori (hanya kategori yang punya program)
        $averagePrograms = ProgramCategory::has('programs')
            ->withCount('programs')
            ->get()
            ->avg('programs_count');

        $stats[] = Stat::make('Rata-rata Program', round($averagePrograms, 1))
            ->description('Per kategori aktif')
            ->color('warning')
            ->icon('heroicon-o-scale')
            ->chart([3, 4, 5, 6, 5, 4, 6])
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-amber-50 to-orange-50 border-l-4 border-amber-500',
            ]);

        return $stats;
    }

    protected function getColumns(): int
    {
        return 3;
    }
}