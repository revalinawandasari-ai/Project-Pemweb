<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('flights', [FlightController::class, 'index'])->name('flight.index');

Route::get('check-booking', [BookingController::class, 'checkBooking'])->name('booking.check');
Route::post('check-booking', [BookingController::class, 'show'])->name('booking.show');

Route::get('flight/booking/{flightNumber}/choose-seat', [BookingController::class, 'chooseSeat'])->name('booking.chooseSeat');
Route::post('flight/booking/{flightNumber}/confirm-seat', [BookingController::class, 'confirmSeat'])->name('booking.confirmSeat');

Route::get('flight/booking/{flightNumber}/passenger-details', [BookingController::class, 'passengerDetails'])->name('booking.passengerDetails');
Route::post('flight/booking/{flightNumber}/save-passenger-details', [BookingController::class, 'savePassengerDetails'])->name('booking.savePassengerDetails');

Route::get('flight/booking/{flightNumber}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('flight/booking/{flightNumber}/payment', [BookingController::class, 'payment'])->name('booking.payment');

Route::post('flight/booking/{flightNumber}', [BookingController::class, 'booking'])->name('booking');

Route::get('flight/{flightNumber}/choose-tier', [FlightController::class, 'show'])->name('flight.show');

Route::get('/flight/{id}/seats/economy', [FlightController::class, 'seatsEconomy'])->name('flight.seats.economy');
Route::get('/flight/{id}/seats/business', [FlightController::class, 'seatsBusiness'])->name('flight.seats.business');

Route::get('/booking-success', [BookingController::class, 'success'])->name('booking.success');