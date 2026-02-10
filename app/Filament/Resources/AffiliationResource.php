<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliationResource\Pages;
use App\Models\Affiliation;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Storage;

class AffiliationResource extends Resource
{
    protected static ?string $model = Affiliation::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    protected static ?string $navigationLabel = 'Afiliasi';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Afiliasi';

    protected static ?string $pluralModelLabel = 'Afiliasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Afiliasi')
                    ->description('Data organisasi mitra')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Organisasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: MWC NU')
                            ->columnSpan(2),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Deskripsi singkat organisasi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media & Dokumen')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('affiliations/logos') 
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('1:1')
                            ->maxSize(2048)
                            ->helperText('Format: JPG/PNG, rasio 1:1')
                            ->columnSpan(1),

                        FileUpload::make('legal_document')
                            ->label('Dokumen PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('affiliations/documents') // Pastikan folder documents
                            ->maxSize(5120) // 5MB
                            ->helperText('Format: PDF, maksimal 5MB')
                            ->preserveFilenames() // Jaga nama asli
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Tautan')
                    ->schema([
                        TextInput::make('external_url')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->size(40)
                    ->circular()
                    ->defaultImageUrl(url('/images/default-logo.png')),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->weight('medium'),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable()
                    ->wrap()
                    ->color('gray'),

                BadgeColumn::make('legal_document')
                    ->label('Dokumen')
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Lihat Dokumen' : 'Tidak ada';
                    })
                    ->colors([
                        'primary' => fn ($state) => !empty($state),
                        'gray' => fn ($state) => empty($state),
                    ])
                    ->url(function ($record) {
                        if ($record->legal_document) {
                            return Storage::url($record->legal_document);
                        }
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->extraAttributes(function ($record) {
                        return [
                            'class' => $record->legal_document ? 'cursor-pointer hover:opacity-80' : 'cursor-not-allowed opacity-50',
                            'title' => $record->legal_document ? 'Klik untuk membuka PDF' : 'Tidak ada dokumen',
                        ];
    }),

                TextColumn::make('external_url')
                    ->label('Website')
                    ->limit(25)
                    ->copyable()
                    ->copyMessage('URL disalin')
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Tambah Afiliasi'),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliations::route('/'),
            'create' => Pages\CreateAffiliation::route('/create'),
            'edit' => Pages\EditAffiliation::route('/{record}/edit'),
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