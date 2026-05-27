<?php

namespace App\Repositories;

use App\Models\Airline;
use App\Interfaces\AirlineRepositoryInterface;

class AirlineRepository implements AirlineRepositoryInterface
{
    public function getAllAirlines()
    {
        return Airline::all();
    }
}