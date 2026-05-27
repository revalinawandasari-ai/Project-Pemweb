<?php

namespace App\Interfaces;

Interface AirportRepositoryInterface
{
    public function getAllAirports();

    public function getAirportBySlug($slug);

    public function getAirportsByIataCode($iataCode);
}
