<?php

namespace App\Filament\Resources\ManagementStructureResource\Widgets;

use App\Models\ManagementStructure;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TopManagementStructureWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = ManagementStructure::count();
        $active = ManagementStructure::where('status', true)->count();
        $inactive = ManagementStructure::where('status', false)->count();
        
        // Cari ketua yang sedang aktif
        $ketua = ManagementStructure::where('status', true)
            ->where(function($query) {
                $query->where('position', 'like', '%ketua%')
                    ->orWhere('position', 'like', '%Ketua%')
                    ->orWhere('position', 'like', '%chair%')
                    ->orWhere('position', 'like', '%Chair%');
            })
            ->orderBy('start_year', 'desc')
            ->first();

        return [
            Stat::make('Total Pengurus', $total)
                ->description('Seluruh pengurus')
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', 'inline'),

            Stat::make('Pengurus Aktif', $active)
                ->description('Sedang menjabat')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->descriptionIcon('heroicon-m-user-group', 'inline'),

            Stat::make('Pengurus Tidak Aktif', $inactive)
                ->description('Masa jabatan selesai')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->descriptionIcon('heroicon-m-clock', 'inline'),

            Stat::make('Ketua', $ketua ? Str::limit($ketua->name, 20) : 'Belum ditentukan')
                ->description($ketua ? $ketua->position : 'Jabatan kosong')
                ->icon('heroicon-o-user')
                ->color('warning')
                ->descriptionIcon('heroicon-m-user-circle', 'inline'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}