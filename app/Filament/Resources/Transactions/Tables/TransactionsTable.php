<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
    ->searchable()
    ->sortable(),
TextColumn::make('flight.flight_number')
    ->label('Flight')
    ->searchable()
    ->sortable(),
TextColumn::make('name')
    ->searchable(),
TextColumn::make('email')
    ->label('Email address')
    ->searchable(),
TextColumn::make('phone')
    ->searchable(),
TextColumn::make('number_of_passengers')
    ->numeric()
    ->sortable(),
TextColumn::make('promo.code')
    ->label('Promo Code')
    ->default('-'),
TextColumn::make('payment_status')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'paid'    => 'success',
        'pending' => 'warning',
        'failed'  => 'danger',
    }),
TextColumn::make('subtotal')
    ->numeric()
    ->prefix('IDR ')
    ->sortable(),
TextColumn::make('grandtotal')
    ->numeric()
    ->prefix('IDR ')
    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
