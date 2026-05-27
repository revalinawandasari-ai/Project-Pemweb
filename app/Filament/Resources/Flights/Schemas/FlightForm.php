<?php

namespace App\Filament\Resources\Flights\Schemas;

use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class FlightForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Flight Information')
                        ->schema([
                            TextInput::make('flight_number')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Select::make('airline_id')
                                ->relationship('airline', 'name')
                                ->required(),
                        ]),

                    Step::make('Flight Segment')
                        ->schema([
                            Repeater::make('flight_segment')
                                ->relationship('segments')
                                ->schema([
                                    TextInput::make('sequence')
                                        ->numeric()
                                        ->required(),
                                    Select::make('airport_id')
                                        ->relationship('airport', 'name')
                                        ->required(),
                                    DateTimePicker::make('time')
                                        ->required(),
                                ])
                                ->collapsed(false)
                                ->minItems(1),
                        ]),                     

                    Step::make('Flight Class')
                        ->schema([
                            Repeater::make('flight_classes')
                                ->relationship('classes')
                                ->schema([
                                    Select::make('class_type')
                                        ->options([
                                            'business' => 'Business',
                                            'economy' => 'Economy',
                                        ])
                                        ->required(),
                                    TextInput::make('price')
                                        ->required()
                                        ->prefix('IDR')
                                        ->numeric()
                                        ->minValue(0),
                                    TextInput::make('total_seats')
                                        ->required()
                                        ->numeric()
                                        ->minValue(1)  
                                        ->label('Total Seats'),
                                    Select::make('facilities')
                                        ->relationship('facilities', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->required(),
                                ])
                
                        ]),                        

                ])->columnSpanFull(),
            ]);
    }
}