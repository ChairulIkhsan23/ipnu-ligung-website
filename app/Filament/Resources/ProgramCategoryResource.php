<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramCategoryResource\Pages;
use App\Models\ProgramCategory;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProgramCategoryResource extends Resource
{
    protected static ?string $model = ProgramCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Kategori Program';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Kategori Program';

    protected static ?string $pluralModelLabel = 'Kategori Program';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kategori Program')
                    ->description('Data utama kategori program')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Sosial, Pendidikan, Kesehatan')
                            ->columnSpan(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                if (blank($state)) return;
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('nama-kategori')
                            ->helperText('URL friendly version of name')
                            ->columnSpan(2),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Deskripsi singkat kategori program')
                            ->helperText('Tampilkan jika diperlukan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->description ? Str::limit($record->description, 50) : null),

                BadgeColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->copyMessage('Slug disalin')
                    ->color('gray'),

                TextColumn::make('programs_count')
                    ->label('Jumlah Program')
                    ->counts('programs')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state == 0 => 'gray',
                        $state <= 5 => 'blue',
                        $state <= 10 => 'green',
                        default => 'purple',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('has_programs')
                    ->label('Memiliki Program')
                    ->placeholder('Semua')
                    ->trueLabel('Dengan Program')
                    ->falseLabel('Tanpa Program')
                    ->queries(
                        true: fn (Builder $query) => $query->has('programs'),
                        false: fn (Builder $query) => $query->doesntHave('programs'),
                    ),
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
                ])
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
                    ->label('Tambah Kategori Program'),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProgramCategories::route('/'),
            'create' => Pages\CreateProgramCategory::route('/create'),
            'edit' => Pages\EditProgramCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}