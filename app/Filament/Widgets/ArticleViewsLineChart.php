<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ArticleViewsLineChart extends ChartWidget
{
    protected static ?string $heading = 'Monitoring Views Artikel';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $period = request()->get('period', 'daily');

        return match ($period) {
            'monthly' => $this->getMonthlyData(),
            'yearly'  => $this->getYearlyData(),
            default   => $this->getDailyData(),
        };
    }

    protected function getDailyData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

        $views = BlogPost::whereNotNull('last_viewed_at')
            ->select(
                DB::raw('DATE(last_viewed_at) as date'),
                DB::raw('SUM(views_today) as total')
            )
            ->where('last_viewed_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'labels' => $days->map(fn ($d) => Carbon::parse($d)->format('d M')),
            'datasets' => [
                [
                    'label' => 'Views Harian',
                    'data' => $days->map(fn ($d) => $views[$d] ?? 0),
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getMonthlyData(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i)->format('Y-m'));

        $views = BlogPost::select(
                DB::raw('DATE_FORMAT(last_viewed_at, "%Y-%m") as month'),
                DB::raw('SUM(views_this_month) as total')
            )
            ->where('last_viewed_at', '>=', now()->subYear())
            ->groupBy('month')
            ->pluck('total', 'month');

        return [
            'labels' => $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M Y')),
            'datasets' => [
                [
                    'label' => 'Views Bulanan',
                    'data' => $months->map(fn ($m) => $views[$m] ?? 0),
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getYearlyData(): array
    {
        $years = collect(range(4, 0))->map(fn ($i) => now()->subYears($i)->year);

        $views = BlogPost::select(
                DB::raw('YEAR(last_viewed_at) as year'),
                DB::raw('SUM(views) as total')
            )
            ->groupBy('year')
            ->pluck('total', 'year');

        return [
            'labels' => $years,
            'datasets' => [
                [
                    'label' => 'Views Tahunan',
                    'data' => $years->map(fn ($y) => $views[$y] ?? 0),
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
