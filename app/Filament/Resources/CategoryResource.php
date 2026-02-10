<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kategori')
                    ->description('Data utama kategori')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Berita, Artikel, Pengumuman')
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
                            ->placeholder('Deskripsi singkat kategori')
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
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->description ? Str::limit($record->description, 50) : null),

                BadgeColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->copyMessage('Slug disalin')
                    ->color('gray'),

                TextColumn::make('posts_count')
                    ->label('Jumlah Artikel')
                    ->counts('posts')
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
                Tables\Filters\TernaryFilter::make('has_posts')
                    ->label('Memiliki Artikel')
                    ->placeholder('Semua Kategori')
                    ->trueLabel('Dengan Artikel')
                    ->falseLabel('Tanpa Artikel')
                    ->queries(
                        true: fn (Builder $query) => $query->has('posts'),
                        false: fn (Builder $query) => $query->doesntHave('posts'),
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Tambah Kategori'),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
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