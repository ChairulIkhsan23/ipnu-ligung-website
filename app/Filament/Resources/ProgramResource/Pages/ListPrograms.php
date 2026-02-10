<?php

namespace App\Filament\Resources\ProgramResource\Pages;

use App\Filament\Resources\ProgramResource;
use App\Filament\Resources\ProgramResource\Widgets\TopProgramWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrograms extends ListRecords
{
    protected static string $resource = ProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Program')
                ->icon('heroicon-o-user-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TopProgramWidget::class,
        ];
    }
}
