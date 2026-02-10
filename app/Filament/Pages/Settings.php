<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class Settings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Akun';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Informasi Profil')
                ->description('Perbarui informasi profil dan foto avatar Anda')
                ->schema([
                    FileUpload::make('avatar')
                        ->label('Foto Profil')
                        ->avatar()
                        ->image()
                        ->directory('avatars')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetWidth('200')
                        ->imageResizeTargetHeight('200')
                        ->maxSize(1024)
                        ->helperText('Ukuran maksimal 1MB, format JPG/PNG')
                        ->columnSpan(['lg' => 2]),

                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama lengkap')
                        ->columnSpan(['lg' => 2]),

                    TextInput::make('email')
                        ->label('Alamat Email')
                        ->email()
                        ->required()
                        ->unique(
                            table: 'users',
                            column: 'email',
                            ignorable: Auth::user()
                        )
                        ->placeholder('contoh@email.com')
                        ->columnSpan(['lg' => 1]),

                    TextInput::make('phone')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->maxLength(20)
                        ->placeholder('0812-3456-7890')
                        ->columnSpan(['lg' => 1]),
                ])
                ->columns(2),

            Section::make('Keamanan Akun')
                ->description('Perbarui kata sandi untuk mengamankan akun Anda')
                ->schema([
                    TextInput::make('current_password')
                        ->label('Kata Sandi Saat Ini')
                        ->password()
                        ->revealable() 
                        ->required(fn ($get) => filled($get('password')))
                        ->dehydrated(false)
                        ->prefixIcon('heroicon-o-lock-closed'),

                    TextInput::make('password')
                        ->label('Kata Sandi Baru')
                        ->password()
                        ->revealable() 
                        ->minLength(8)
                        ->confirmed()
                        ->dehydrated(fn ($state) => filled($state))
                        ->helperText('Minimal 8 karakter')
                        ->prefixIcon('heroicon-o-key'),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Kata Sandi Baru')
                        ->password()
                        ->revealable() 
                        ->dehydrated(false)
                        ->prefixIcon('heroicon-o-key'), 
                ])
                ->columns(2),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $user = Auth::user();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            unset($data['password_confirmation']);
            unset($data['current_password']);

            $user->update($data);

            Notification::make()
                ->title('Pengaturan berhasil disimpan')
                ->body('Perubahan telah diterapkan pada akun Anda.')
                ->success()
                ->send();

            $this->mount();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal menyimpan pengaturan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}