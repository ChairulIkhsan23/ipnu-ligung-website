<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class DashboardProgramTable extends BaseWidget
{
    protected int | string | array $columnSpan = 1;

    // Urutan tampil di dashboard (lebih kecil = lebih atas)
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Program Sedang Berlangsung';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Program::query()
                    ->where('status', 'ongoing') 
                    ->orderBy('start_date', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Program')
                    ->limit(30)
                    ->weight('medium')
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable()
                    ->color('gray'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'ongoing',
                        'info'    => 'upcoming',
                        'success' => 'completed',
                        'danger'  => 'cancelled',
                        'gray'    => 'draft',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'ongoing'   => 'Berlangsung',
                        'upcoming'  => 'Akan Datang',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'draft'     => 'Draft',
                        default     => ucfirst($state),
                    }),
            ])
            ->paginated(false)
            ->description('5 program yang sedang berjalan')
            ->emptyStateHeading('Belum ada program berjalan')
            ->emptyStateDescription('Program dengan status "Berlangsung" akan tampil di sini')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
