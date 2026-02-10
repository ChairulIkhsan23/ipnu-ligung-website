<?php

namespace App\Filament\Resources\ManagementStructureResource\Pages;

use App\Filament\Resources\ManagementStructureResource;
use App\Filament\Resources\ManagementStructureResource\Widgets\TopManagementStructureWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ManagementStructure;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ManagementStructureExport;

class ListManagementStructures extends ListRecords
{
    protected static string $resource = ManagementStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Pengurus')
                ->icon('heroicon-o-user-plus'),

            Actions\ActionGroup::make([
                // PDF Export
                Action::make('export_pdf')
                    ->label('PDF Document')
                    ->icon('heroicon-o-document-text')
                    ->color('danger')
                    ->action(function () {
                        $records = ManagementStructure::all();
                        $pdf = Pdf::loadView('exports.management-structure-exports', compact('records'));
                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            'struktur-kepengurusan-' . date('Y-m-d') . '.pdf'
                        );
                    }),

                /**
                 * Masih belum fix anjay excel export
                */
                // // Excel Export dari Filament Excel
                // Action::make('export_excel')
                //     ->label('Export Excel')
                //     ->icon('heroicon-o-table-cells')
                //     ->color('success')
                //     ->action(function () {
                //         return Excel::download(
                //             new ManagementStructureExport(), 
                //             'struktur-kepengurusan-' . date('Y-m-d') . '.xlsx'
                //         );
                //     })
            ])
            ->label('Export Data')
            ->icon('heroicon-o-arrow-down-tray')
            ->button()
            ->color('primary'),
        
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TopManagementStructureWidget::class,
        ];
    }
}
