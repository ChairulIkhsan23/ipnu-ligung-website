<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManagementStructureResource\Pages;
use App\Models\ManagementStructure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ManagementStructureResource extends Resource
{
    protected static ?string $model = ManagementStructure::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Struktur Kepengurusan';

    protected static ?string $navigationGroup = 'Organisasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pengurus';

    protected static ?string $pluralModelLabel = 'Struktur Kepengurusan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pribadi')
                    ->description('Data diri pengurus')
                    ->schema([
                        TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Ratu Ageng Gandasari')
                                ->columnSpan(2),

                        TextInput::make('nomor_anggota')
                            ->label('Nomor Anggota')
                            ->disabled()
                            ->dehydrated()
                            ->default(null)
                            ->helperText('Otomatis digenerate'),
                            
                        TextInput::make('position')
                                ->label('Jabatan')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Ketua, Sekretaris, Bendahara')
                                ->columnSpan(2),

                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Dusun Sukamaju Desa Ligung Lor')
                            ->columnSpan(2),

                        TextInput::make('no_telp')
                            ->label('Nomor Telepon')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('+62')
                            ->columnSpan(2),


                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('management/photos')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:4')
                            ->maxSize(2048)
                            ->helperText('Rasio 3:4, ukuran optimal 300x400px')
                            ->columnSpan(1),

                        Textarea::make('motto')
                            ->label('Moto/Kata-kata')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Kata-kata motivasi atau motto pribadi')
                            ->helperText('Maksimal 500 karakter')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Periode & Status')
                    ->description('Masa jabatan dan status pengurus')
                    ->schema([
                        TextInput::make('start_year')
                            ->label('Tahun Mulai')
                            ->required()
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(now()->year + 1)
                            ->placeholder('2024')
                            ->columnSpan(1),

                        TextInput::make('end_year')
                            ->label('Tahun Selesai')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(now()->year + 5)
                            ->placeholder('2029')
                            ->columnSpan(1)
                            ->helperText('Kosongkan jika masih menjabat'),


                        Toggle::make('status')
                            ->label('Status Aktif')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->size(50)
                    ->circular()
                    ->defaultImageUrl(url('/images/default-profile.png')),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->motto ? Str::limit($record->motto, 50) : null)
                    ->limit(30),
                
                TextColumn::make('nomor_anggota')
                    ->label('Nomor Anggota')
                    ->searchable(),

                BadgeColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable()
                    ->colors([
                        'primary' => fn ($state) => str_contains(strtolower($state), 'ketua'),
                        'success' => fn ($state) => str_contains(strtolower($state), 'sekretaris'),
                        'warning' => fn ($state) => str_contains(strtolower($state), 'bendahara'),
                        'info' => fn ($state) => str_contains(strtolower($state), 'departmen'),
                        'gray' => fn ($state) => str_contains(strtolower($state), 'anggota'),
                    ]),
                
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(30) 
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->state(fn ($record) =>
                        $record->end_year
                            ? "{$record->start_year} - {$record->end_year}"
                            : "{$record->start_year} - Sekarang"
                    )
                    ->badge()
                    ->color('info')
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy('start_year', $direction);
                    }),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ])
                    ->multiple()
                    ->preload(),

                Filter::make('position')
                    ->form([
                        TextInput::make('position_search')
                            ->label('Cari Jabatan')
                            ->placeholder('Contoh: ketua, sekretaris'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['position_search'],
                            fn (Builder $query, $search): Builder => 
                                $query->where('position', 'like', "%{$search}%")
                        );
                    }),

                Filter::make('period')
                    ->form([
                        TextInput::make('period_search')
                            ->label('Cari Periode')
                            ->placeholder('Contoh: 2024'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['period_search'],
                            fn (Builder $query, $search): Builder => 
                                $query->where('period', 'like', "%{$search}%")
                        );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->color('primary'),

                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Tambah Pengurus'),
            ])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManagementStructures::route('/'),
            'create' => Pages\CreateManagementStructure::route('/create'),
            'edit' => Pages\EditManagementStructure::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $active = static::getModel()::where('status', true)->count();
        $total = static::getModel()::count();
        
        if ($total === 0) return 'gray';
        if ($active === $total) return 'success';
        if ($active > 0) return 'warning';
        return 'danger';
    }
}