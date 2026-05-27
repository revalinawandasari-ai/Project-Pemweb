<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\AirportRepositoryInterface;
use App\Interfaces\AirlineRepositoryInterface;
use App\Interfaces\FlightRepositoryInterface;

class FlightController extends Controller
{
    private AirportRepositoryInterface $airportRepository;
    private AirlineRepositoryInterface $airlineRepository;
    private FlightRepositoryInterface $flightRepository;

    public function __construct(
        AirportRepositoryInterface $airportRepository,
        AirlineRepositoryInterface $airlineRepository, 
        FlightRepositoryInterface $flightRepository)
     {
        $this->airportRepository = $airportRepository;
        $this->airlineRepository = $airlineRepository;
        $this->flightRepository = $flightRepository;
    }
    public function index(Request $request)
    {
        $departure = $this->airportRepository->getAirportsByIataCode($request->departure);
        $arrival = $this->airportRepository->getAirportsByIataCode($request->arrival);
        
        $flights = $this->flightRepository->getAllFlight([
            'departure' => $departure->id ?? null,
            'arrival' => $arrival->id ?? null,
            'date' => $request->date ?? null,
        ]);

        $airlines = $this->airlineRepository->getAllAirlines();

        return view('pages.flight.index', compact('flights','airlines' ));
    }

    public function show($flightNumber)
    {
        $flight = $this->flightRepository->getFlightByFlightNumber($flightNumber);

        return view('pages.flight.show', compact('flight'));
    } 

    public function seatsEconomy($id)
    {
        $flight = Flight::findOrFail($id);
        return view('pages.flight.seats.economy', compact('flight'));
    }
}