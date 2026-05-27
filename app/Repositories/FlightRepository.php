<?php

namespace App\Repositories;

use App\Models\Flight;
use App\Interfaces\FlightRepositoryInterface;

class FlightRepository implements FlightRepositoryInterface
{
    public function getAllFlight($filter = null)
    {
        $flights = Flight::query();

        if (!empty($filter['departure'])) {
            $flights->whereHas('segments', function ($query) use ($filter) {
                $query->where('airport_id', $filter['departure'])
                    ->where('sequence', 1);
            });
        }

        if (!empty($filter['arrival'])) {
            $flights->whereHas('segments', function ($query) use ($filter) {
                $query->where('airport_id', $filter['arrival'])
                    ->whereRaw('sequence = (SELECT MAX(s2.sequence) FROM flight_segments s2 WHERE s2.flight_id = flight_segments.flight_id)');
            });
        }

        if (!empty($filter['date'])) {
            $flights->whereHas('segments', function ($query) use ($filter) {
                $query->whereDate('time', $filter['date']);
            });
        }

        return $flights->get();
    }

    public function getFlightByFlightNumber($flightNumber)
    {
        return Flight::with(['classes', 'seats'])
                    ->where('flight_number', $flightNumber)
                    ->firstOrFail();
    }
}