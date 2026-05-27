<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater; 


class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
    ->columns(1)
    ->schema([
        TextInput::make('code')
            ->required(),
        Select::make('flight_id')
            ->relationship('flight', 'flight_number')
            ->required(),
        Select::make('flight_class_id')
            ->relationship('class', 'class_type')
            ->required(),
    ]),

Section::make('Informasi Penumpang')
    ->columns(1)
    ->schema([
        TextInput::make('number_of_passengers')
            ->required(),
        TextInput::make('name')
            ->required(),
        TextInput::make('email')
            ->label('Email address')
            ->email()
            ->required(),
        TextInput::make('phone')
            ->tel()
            ->required(),
        Section::make('Daftar Penumpang')
            ->schema([
                Repeater::make('passenger')
                    ->relationship('passengers')
                    ->schema([
                        Select::make('flight_seat_id')
                            ->label('Seat Name')
                            ->relationship('seat', 'name')
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('date_of_birth')
                            ->tel()
                            ->required(),
                        TextInput::make('nationality')
                            ->tel()
                            ->required(),
                    ])
            ])

    ]),

Section::make('Pembayaran')
    ->columns(1)
    ->schema([
        Select::make('promo_code_id')
            ->relationship('promo', 'code')
            ->default(null),
        Select::make('payment_status')
            ->options([
                'pending' => 'Pending',
                'paid'    => 'Paid',
                'failed'  => 'Failed',
            ])
            ->default('pending')
            ->required(),
        TextInput::make('subtotal')
            ->numeric()
            ->prefix('IDR')
            ->default(null),
        TextInput::make('grandtotal')
            ->numeric()
            ->prefix('IDR')
            ->default(null),
    ]),
            ]);
    }
}