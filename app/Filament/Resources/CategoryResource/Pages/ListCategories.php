<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CategoryResource\Widgets\TopKategoriBlogPostWidget;
use App\Filament\Resources\CategoryResource\Widgets\CategoryEngagementWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori Artikel')
                ->icon('heroicon-o-user-plus'),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            TopKategoriBlogPostWidget::class,
            CategoryEngagementWidget::class,
        ];
    }
}
