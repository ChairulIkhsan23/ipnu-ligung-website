<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use App\Filament\Resources\BlogPostResource\Widgets\TopBlogPostWidget;
use App\Filament\Resources\BlogPostResource\Widgets\ArticlePerformanceWidget;
use App\Filament\Resources\BlogPostResource\Widgets\AuthorPerformanceWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Artikel')
                ->icon('heroicon-o-user-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TopBlogPostWidget::class,
            ArticlePerformanceWidget::class,
            AuthorPerformanceWidget::class,
        ];
    }
}
