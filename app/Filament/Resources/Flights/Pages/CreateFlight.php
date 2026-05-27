<?php

namespace App\Filament\Resources\Flights\Pages;

use App\Models\Flight;
use App\Filament\Resources\Flights\FlightResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFlight extends CreateRecord
{
    protected static string $resource = FlightResource::class;

    protected function afterCreate(): void
    {
        $this->record->generateSeats();
    }
}
