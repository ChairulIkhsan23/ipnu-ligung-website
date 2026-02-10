<?php

namespace App\Filament\Resources\ManagementStructureResource\Pages;

use App\Filament\Resources\ManagementStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateManagementStructure extends CreateRecord
{
    protected static string $resource = ManagementStructureResource::class;

    public function getTitle(): string
    {
        return 'Tambah Data Pengurus';
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('back')
            ->label('Kembali')
            ->color('gray')
            ->url($this->getResource()::getUrl('index'))
            ->icon('heroicon-o-arrow-left'),
            
            $this->getCreateFormAction()
            ->label('Simpan')
            ->icon('heroicon-o-check'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
