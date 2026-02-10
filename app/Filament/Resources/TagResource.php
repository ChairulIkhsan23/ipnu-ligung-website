<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Filament\Resources\TagResource\Widgets;


class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationLabel = 'Tag';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'Tag';

    protected static ?string $pluralModelLabel = 'Tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Tag')
                    ->description('Data tag untuk kategorisasi konten')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Tag')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Pendidikan, Sosial, NU')
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
                            ->placeholder('nama-tag')
                            ->helperText('URL friendly version of name')
                            ->columnSpan(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Tag')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->slug),

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

                TextColumn::make('programs_count')
                    ->label('Jumlah Program')
                    ->counts('programs')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state == 0 => 'gray',
                        $state <= 3 => 'blue',
                        $state <= 6 => 'green',
                        default => 'purple',
                    })
                    ->sortable(),

                TextColumn::make('total_usage')
                    ->label('Total Penggunaan')
                    ->getStateUsing(function ($record) {
                        return ($record->posts_count ?? 0) + ($record->programs_count ?? 0);
                    })
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state == 0 => 'gray',
                        $state <= 5 => 'blue',
                        $state <= 10 => 'green',
                        $state <= 20 => 'orange',
                        default => 'red',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('usage')
                    ->label('Tingkat Penggunaan')
                    ->options([
                        'unused' => 'Belum digunakan',
                        'low' => 'Penggunaan rendah (1-5)',
                        'medium' => 'Penggunaan sedang (6-15)',
                        'high' => 'Penggunaan tinggi (16+)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!isset($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'unused' => $query->whereDoesntHave('posts')->whereDoesntHave('programs'),
                            'low' => $query->where(function ($q) {
                                $q->has('posts', '<=', 5)
                                ->orHas('programs', '<=', 5);
                            }),
                            'medium' => $query->where(function ($q) {
                                $q->has('posts', '<=', 15)
                                ->orHas('programs', '<=', 15);
                            }),
                            'high' => $query->where(function ($q) {
                                $q->has('posts', '>', 15)
                                ->orHas('programs', '>', 15);
                            }),
                            default => $query,
                        };
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
                    ->label('Tambah Tag'),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
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

    public static function getWidgets(): array
    {
        return [
            Widgets\TopTagsWidget::class,
        ];
    }

}