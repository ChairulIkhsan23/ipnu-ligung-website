<?php

namespace App\Filament\Resources\ManagementStructureResource\Pages;

use App\Filament\Resources\ManagementStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManagementStructure extends EditRecord
{
    protected static string $resource = ManagementStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Data Pengurus')
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
