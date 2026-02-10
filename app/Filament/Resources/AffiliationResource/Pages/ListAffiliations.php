<?php

namespace App\Filament\Resources\AffiliationResource\Pages;

use App\Filament\Resources\AffiliationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAffiliations extends ListRecords
{
    protected static string $resource = AffiliationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Afiliasi')
                ->icon('heroicon-o-user-plus'),
        ];
    }
}
