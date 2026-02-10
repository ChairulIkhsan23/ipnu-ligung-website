<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Artikel';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Artikel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Artikel')
                    ->description('Konten utama artikel')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Judul artikel yang menarik')
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
                            ->placeholder('judul-artikel')
                            ->helperText('URL friendly version of title')
                            ->columnSpan(2),

                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Ringkasan singkat artikel')
                            ->helperText('Tampilkan di halaman daftar artikel')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->fileAttachmentsDirectory('blog/attachments')
                            ->columnSpanFull()
                            ->helperText('Tulis konten artikel lengkap disini'),
                    ])
                    ->columns(2),

                Section::make('Media & Kategori')
                    ->description('Gambar dan kategori artikel')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('blog/thumbnails')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->maxSize(2048)
                            ->helperText('Rasio 16:9, ukuran optimal 1200x675px')
                            ->columnSpan(1),

                        FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image()
                            ->directory('blog/og-images')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1.91:1')
                            ->maxSize(2048)
                            ->helperText('Rasio 1.91:1 (Facebook/Twitter)')
                            ->columnSpan(1),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpan(1),

                        Select::make('author_id')
                            ->label('Author')
                            ->relationship('author', 'name')
                            ->default(fn () => Auth::user() ? Auth::user()->id : null)
                            ->afterStateHydrated(function (Select $component) {
                                if (!$component->getState()) {
                                    $component->state(Auth::id());
                                }
                            })
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Publikasi & SEO')
                    ->description('Pengaturan publikasi dan optimasi mesin pencari')
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->default(now())
                            ->required(),

                        Toggle::make('is_published')
                            ->label('Status Publikasi')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline()
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
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Section::make('Tag Artikel')
                    ->schema([
                        Select::make('tags')
                            ->label('Tag')
                            ->relationship('tags', 'name')
                            ->preload()
                            ->multiple()
                            ->searchable()
                            ->helperText('Pilih tag untuk artikel ini'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->size(50)
                    ->circular(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('medium')
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('views')
                    ->label('Dilihat')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state > 1000 => 'danger',
                        $state > 500 => 'warning',
                        $state > 100 => 'success',
                        default => 'gray',
                    }),

                IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('published_at')
                    ->label('Dipublikasi')
                    ->dateTime('d M Y')
                    ->sortable(),
                
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),


                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('author')
                    ->label('Penulis')
                    ->relationship('author', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi')
                    ->placeholder('Semua')
                    ->trueLabel('Dipublikasi')
                    ->falseLabel('Draft'),
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
                    ->label('Tambah Artikel'),
            ])
            ->defaultSort('published_at', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_published', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $published = static::getModel()::where('is_published', true)->count();
        $total = static::getModel()::count();
        
        if ($total === 0) return 'gray';
        if ($published === $total) return 'success';
        if ($published > 0) return 'warning';
        return 'danger';
    }
}