<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\TransactionOverview;


class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;
    
    public function getHeaderWidgets(): array
    {
        return [
            TransactionOverview::class
        ];
    }
   
protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New transaction'),
        ];
    }
}