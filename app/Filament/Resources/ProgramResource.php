<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Models\Program;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Str;
use App\Enums\Visibility;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Program';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Program';

    protected static ?string $pluralModelLabel = 'Program';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Program')
                    ->description('Data utama program')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Program')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Baksos Ramadhan 2024')
                            ->columnSpan(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, callable $set, $get) {
                                if (blank($get('slug'))) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('nama-program')
                            ->helperText('URL friendly version of title')
                            ->columnSpan(2)
                            ->unique(ignoreRecord: true),

                        Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->required()
                            ->rows(2)
                            ->maxLength(200)
                            ->placeholder('Deskripsi singkat untuk tampilan list')
                            ->helperText('Maksimal 200 karakter')
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->required()
                            ->fileAttachmentsDirectory('program/attachments')
                            ->columnSpanFull()
                            ->helperText('Tulis penjelasan lengkap program disini'),
                    ])
                    ->columns(2),

                Section::make('Kategori & Media')
                    ->description('Pengelompokan dan gambar program')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpan(1),

                        FileUpload::make('cover_image')
                            ->label('Cover Image')
                            ->image()
                            ->directory('program/covers')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->maxSize(2048)
                            ->helperText('Rasio 16:9, ukuran optimal 1200x675px')
                            ->columnSpan(1),
                        
                        Section::make('Dokumentasi')
                            ->description('Catatan: Dokumentasi ditambahkan ketika Program sudah selesai')
                            ->schema([
                                TextInput::make('documentation')
                                    ->label('Link Dokumentasi (Google Drive)')
                                    ->url()
                                    ->placeholder('https://drive.google.com/drive/folders/...')
                                    ->helperText('Link folder Google Drive berisi dokumentasi program')
                                    ->columnSpanFull()
                                    ->maxLength(500),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columns(2),

                Section::make('Jadwal & Status')
                    ->description('Waktu pelaksanaan dan status program')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now())
                            ->columnSpan(1),

                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->nullable()
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'upcoming' => 'Akan Datang',
                                'ongoing' => 'Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->columnSpan(1),

                        Toggle::make('is_featured')
                            ->label('Program Unggulan')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('gray')
                            ->inline()
                            ->helperText('Tampilkan di halaman utama')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Pengaturan & SEO')
                    ->description('Visibilitas dan optimasi mesin pencari')
                    ->schema([
                        Select::make('visibility')
                            ->label('Visibilitas')
                            ->required()
                            ->options([
                                Visibility::PUBLIC->value => 'Publik',
                                Visibility::PRIVATE->value => 'Privat',
                            ])
                            ->default(Visibility::PUBLIC->value)
                            ->helperText('Siapa yang bisa melihat program ini')
                            ->columnSpan(1),

                        TextInput::make('person_in_charge')
                            ->label('Penanggung Jawab')
                            ->maxLength(255)
                            ->placeholder('Nama penanggung jawab program')
                            ->columnSpan(1),

                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255)
                            ->placeholder('Judul untuk SEO')
                            ->helperText('Maksimal 60 karakter')
                            ->columnSpan(1),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(2)
                            ->maxLength(160)
                            ->placeholder('Deskripsi untuk SEO')
                            ->helperText('Maksimal 160 karakter')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Tag Program')
                    ->schema([
                        Select::make('tags')
                            ->label('Tag')
                            ->relationship('tags', 'name')
                            ->preload()
                            ->multiple()
                            ->searchable()
                            ->helperText('Pilih tag untuk program ini'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->size(50)
                    ->circular(),

                TextColumn::make('title')
                    ->label('Judul Program')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('medium')
                    ->description(fn ($record) => Str::limit($record->short_description, 50))
                    ->wrap(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray'    => 'draft',
                        'info'    => 'upcoming',
                        'warning' => 'ongoing',
                        'success' => 'completed',
                        'danger'  => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft'     => 'Draft',
                        'upcoming'  => 'Akan Datang',
                        'ongoing'   => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => ucfirst($state),
                    })
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-s-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
                
                BadgeColumn::make('visibility')
                    ->label('Visibilitas')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color())
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
                        'draft' => 'Draft',
                        'upcoming' => 'Akan Datang',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple()
                    ->preload(),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),

                TernaryFilter::make('is_featured')
                    ->label('Program Unggulan')
                    ->placeholder('Semua')
                    ->trueLabel('Unggulan')
                    ->falseLabel('Biasa'),

                SelectFilter::make('visibility')
                    ->label('Visibilitas')
                    ->options([
                        Visibility::PUBLIC->value => 'Publik',
                        Visibility::PRIVATE->value => 'Privat',
                    ])
                    ->placeholder('Semua'),
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
                    ->label('Tambah Program'),
            ])
            ->defaultSort('start_date', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
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