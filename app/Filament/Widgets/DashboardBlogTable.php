<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DashboardBlogTable extends BaseWidget
{
    protected int | string | array $columnSpan = '1';

    // Urutan tampil (setelah Stats & Chart)
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Artikel Terpopuler';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BlogPost::query()
                    ->where('is_published', true)
                    ->orderByDesc('views')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(30)
                    ->weight('medium')
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->color(fn ($state) => match (true) {
                        $state > 1000 => 'danger',
                        $state > 500  => 'warning',
                        $state > 100  => 'success',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasi')
                    ->date('d M Y')
                    ->sortable()
                    ->color('gray'),
            ])
            ->paginated(false)
            ->description('5 artikel dengan jumlah pembaca terbanyak')
            ->emptyStateHeading('Belum ada artikel populer')
            ->emptyStateDescription('Artikel akan muncul setelah dipublikasikan')
            ->emptyStateIcon('heroicon-o-newspaper');
    }
}
