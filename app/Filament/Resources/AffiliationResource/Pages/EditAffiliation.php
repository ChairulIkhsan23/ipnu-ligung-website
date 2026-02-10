<?php

namespace App\Filament\Resources\AffiliationResource\Pages;

use App\Filament\Resources\AffiliationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliation extends EditRecord
{
    protected static string $resource = AffiliationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Afiliasi')
                ->icon('heroicon-o-trash')
                ->modalDescription('Apakah Anda yakin ingin menghapus tag ini? Data tidak dapat dikembalikan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('back')
            ->label('Batal')
            ->color('gray')
            ->url($this->getResource()::getUrl('index'))
            ->icon('heroicon-o-arrow-left'),
            
            $this->getSaveFormAction()
            ->label('Simpan Perubahan')
            ->icon('heroicon-o-check'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
