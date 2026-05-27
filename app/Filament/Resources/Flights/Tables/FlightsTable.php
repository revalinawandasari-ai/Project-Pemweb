<?php

namespace App\Filament\Resources\Flights\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class FlightsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('flight_number')
                    ->searchable(),
                TextColumn::make('airline.name')
                    ->label('Airline')
                    ->searchable(),
                TextColumn::make('route_and_duration')
                    ->label('Route & Duration')
                    ->getStateUsing(function ($record) {
                        $segments = $record->segments()->with('airport')->orderBy('sequence')->get();

                        if ($segments->count() < 2) return '-';

                        $departure = $segments->first();
                        $arrival   = $segments->last();

                        $from = $departure->airport->iata_code ?? '-';
                        $to   = $arrival->airport->iata_code ?? '-';
                        $dep  = \Carbon\Carbon::parse($departure->time)->format('d F Y H:i');
                        $arr  = \Carbon\Carbon::parse($arrival->time)->format('d F Y H:i');

                        return "{$from} - {$to} | {$dep} - {$arr}";
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
