<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DashboardChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Statistik Program';
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $programData = Program::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Program',
                    'data' => [
                        $programData['ongoing'] ?? 0,
                        $programData['upcoming'] ?? 0,
                        $programData['completed'] ?? 0,
                        $programData['cancelled'] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#10b981', // ongoing
                        '#f59e0b', // upcoming
                        '#6366f1', // completed
                        '#ef4444', // cancelled
                    ],
                ],
            ],
            'labels' => [
                'Berlangsung',
                'Akan Datang',
                'Selesai',
                'Dibatalkan',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
