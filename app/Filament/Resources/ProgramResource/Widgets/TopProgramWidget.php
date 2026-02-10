<?php

namespace App\Filament\Resources\ProgramResource\Widgets;

use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TopProgramWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];
        
        // Total Program
        $totalPrograms = Program::count();
        $stats[] = Stat::make('Total Program', $totalPrograms)
            ->description('Semua program yang dibuat')
            ->color('primary')
            ->icon('heroicon-o-calendar')
            ->chart($this->generateChartData('total'))
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-500',
            ]);

        // Program Berlangsung
        $ongoingPrograms = Program::where('status', 'ongoing')->count();
        $stats[] = Stat::make('Sedang Berlangsung', $ongoingPrograms)
            ->description('Program aktif saat ini')
            ->color('success')
            ->icon('heroicon-o-play-circle')
            ->chart($this->generateChartData('ongoing'))
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-l-4 border-green-500',
            ]);

        // Program Unggulan
        $featuredPrograms = Program::where('is_featured', true)->count();
        $stats[] = Stat::make('Program Unggulan', $featuredPrograms)
            ->description('Ditampilkan di halaman utama')
            ->color('warning')
            ->icon('heroicon-o-star')
            ->chart($this->generateChartData('featured'))
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-amber-50 to-orange-50 border-l-4 border-amber-500',
            ]);

        // Program Akan Datang
        $upcomingPrograms = Program::where('status', 'upcoming')->count();
        $stats[] = Stat::make('Akan Datang', $upcomingPrograms)
            ->description('Program yang akan dilaksanakan')
            ->color('info')
            ->icon('heroicon-o-clock')
            ->chart($this->generateChartData('upcoming'))
            ->extraAttributes([
                'class' => 'bg-gradient-to-br from-cyan-50 to-sky-50 border-l-4 border-cyan-500',
            ]);

        return $stats;
    }

    protected function getColumns(): int
    {
        return 4;
    }

    /**
     * Generate chart data
     */
    private function generateChartData(string $type): array
    {
        return match($type) {
            'total' => [5, 10, 15, 20, 25, 30, 35],
            'ongoing' => [1, 2, 3, 5, 8, 10, 12],
            'featured' => [1, 2, 1, 3, 2, 4, 3],
            'upcoming' => [2, 3, 5, 7, 10, 12, 15],
            default => [1, 2, 3, 4, 3, 2, 1],
        };
    }

    /**
     * Get program distribution by status
     */
    private function getStatusDistribution(): array
    {
        return Program::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}