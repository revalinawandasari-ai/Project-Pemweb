<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Interfaces\AirportRepositoryInterface;  
use App\Repositories\AirportRepository; 
use App\Interfaces\FlightRepositoryInterface;
use App\Repositories\FlightRepository;
use App\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;        

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FlightRepositoryInterface::class, FlightRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    public function boot(): void
{
    if (request()->hasHeader('ngrok-skip-browser-warning') || 
        str_contains(request()->getHost(), 'ngrok-free.dev')) {
        URL::forceRootUrl('https://' . request()->getHost());
        URL::forceScheme('https');
    }    
}
}