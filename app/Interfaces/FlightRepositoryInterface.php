<?php

namespace App\Interfaces;

Interface FlightRepositoryInterface
{
    public function getAllFlight($filter = null);

    public function getFlightByFlightNumber($flightNumber);
}
