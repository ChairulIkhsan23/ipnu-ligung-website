<?php

namespace App\Filament\Resources\ProgramCategoryResource\Pages;

use App\Filament\Resources\ProgramCategoryResource;
use App\Filament\Resources\ProgramCategoryResource\Widgets\TopProgramCategoryWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgramCategories extends ListRecords
{
    protected static string $resource = ProgramCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori Program')
                ->icon('heroicon-o-user-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TopProgramCategoryWidget::class,
        ];
    }
}
