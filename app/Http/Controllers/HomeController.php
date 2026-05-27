<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\AirportRepositoryInterface;

class HomeController extends Controller
{
    private AirportRepositoryInterface $airportRepository;

    public function __construct(AirportRepositoryInterface $airportRepository)
    {
        $this->airportRepository = $airportRepository;
    }
    public function index() 
    {
        $airports = $this->airportRepository->getAllAirports(); //$airports

        return view('pages.home', compact('airports'));
    }
    
}
