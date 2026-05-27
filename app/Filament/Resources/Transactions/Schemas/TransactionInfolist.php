<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Transaction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Transaction Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('code'),
                        TextEntry::make('flight_id')->numeric(),
                        TextEntry::make('flight_class_id')->numeric(),
                        TextEntry::make('name'),
                        TextEntry::make('email')->label('Email address'),
                        TextEntry::make('phone'),
                        TextEntry::make('number_of_passengers')->numeric(),
                        TextEntry::make('promo_code_id')->numeric()->placeholder('-'),
                        TextEntry::make('payment_status')->badge(),
                        TextEntry::make('subtotal')->numeric()->placeholder('-'),
                        TextEntry::make('grandtotal')->numeric()->placeholder('-'),
                        TextEntry::make('created_at')->dateTime()->placeholder('-'),
                        TextEntry::make('updated_at')->dateTime()->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Transaction $record): bool => $record->trashed()),
                    ]),

                Section::make('Daftar Penumpang')
                    ->schema([
                        RepeatableEntry::make('passengers')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('seat.name')->label('Seat Name'),
                                TextEntry::make('name')->label('Name'),
                                TextEntry::make('date_of_birth')->label('Date of birth'),
                                TextEntry::make('nationality')->label('Nationality'),
                            ]),
                    ]),

                Section::make('Pembayaran')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('IDR'),
                        TextEntry::make('grandtotal')
                            ->label('Grand Total')
                            ->money('IDR'),
                        TextEntry::make('promo_code_id')
                            ->label('Promo')
                            ->placeholder('-'),
                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge(),
                    ]),
            ]);
    }
}