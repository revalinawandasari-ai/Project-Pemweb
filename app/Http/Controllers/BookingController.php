<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\FlightRepositoryInterface;
use App\Http\Requests\StorePassengerDetailRequest;
use App\Http\Requests\BookingShowRequest;

class BookingController extends Controller
{
    private FlightRepositoryInterface $flightRespository;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        FlightRepositoryInterface $flightRespository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->flightRespository = $flightRespository;
        $this->transactionRepository = $transactionRepository;
    }

    public function booking(Request $request, $flightNumber)
    {
        $this->transactionRepository->saveTransactionDataToSession($request->all());

        return redirect()->route('booking.chooseSeat', ['flightNumber' => $flightNumber]);
    }

    public function chooseSeat(Request $request, $flightNumber)
    {
        $transaction = $this->transactionRepository->getTransactionDataFromSession();
        $flight = $this->flightRespository->getFlightByFlightNumber($flightNumber);
        $tier = $flight->classes->find($transaction['flight_class_id']);

        return view('pages.booking.choose-seat', compact('transaction', 'flight', 'tier'));
    }
    
    public function confirmSeat(Request $request, $flightNumber)
    {
        $this->transactionRepository->saveTransactionDataToSession($request->all());

        return redirect()->route('booking.passengerDetails', ['flightNumber' => $flightNumber]);
    }

    public function passengerDetails(Request $request, $flightNumber)
    {
        $transaction = $this->transactionRepository->getTransactionDataFromSession();
        $flight = $this->flightRespository->getFlightByFlightNumber($flightNumber);
        $tier = $flight->classes->find($transaction['flight_class_id']);
       
        return view('pages.booking.passenger-details', compact('transaction', 'flight', 'tier'));
    }

    public function savePassengerDetails(StorePassengerDetailRequest $request, $flightNumber)
    {
        $oldTransaction = $this->transactionRepository->getTransactionDataFromSession();
    
        $this->transactionRepository->saveTransactionDataToSession($request->all());
    
        // Pertahankan data penting dari session lama
        $preserve = ['selected_seats', 'flight_class_id', 'flight_id', 'seat'];
        foreach ($preserve as $key) {
            if (isset($oldTransaction[$key]) && !$request->has($key)) {
                $this->transactionRepository->saveTransactionDataToSession([
                    $key => $oldTransaction[$key]
                ]);
            }
        }

        return redirect()->route('booking.checkout', ['flightNumber' => $flightNumber]);
    }


    public function checkout($flightNumber)
    {
        $transaction = $this->transactionRepository->getTransactionDataFromSession();
        $flight = $this->flightRespository->getFlightByFlightNumber($flightNumber);
        $tier = $flight->classes->find($transaction['flight_class_id']);
      

        return view('pages.booking.checkout', compact('transaction', 'flight', 'tier'));
    }

public function payment(Request $request, $flightNumber)
{
    $oldSession = $this->transactionRepository->getTransactionDataFromSession();
    
    $this->transactionRepository->saveTransactionDataToSession($request->all());
    
    // Pertahankan promo_code dari session Livewire
    if (!$request->has('promo_code') && isset($oldSession['promo_code'])) {
        $this->transactionRepository->saveTransactionDataToSession([
            'promo_code' => $oldSession['promo_code']
        ]);
    }

    $transaction = $this->transactionRepository->saveTransaction(
        $this->transactionRepository->getTransactionDataFromSession()
    );

    \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    \Midtrans\Config::$isProduction = config('midtrans.isProduction');
    \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is3ds');
    \Midtrans\Config::$curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ];

    $params = [
        'transaction_details' => [
            'order_id'     => (string) $transaction->code,
            'gross_amount' => (int) $transaction->grandtotal,
        ],
        'customer_details' => [
            'first_name' => $transaction->name,
            'email'      => $transaction->email,
            'phone'      => $transaction->phone,
        ]
    ];

    try {
        $response = \Midtrans\Snap::createTransaction($params);
        $paymentUrl = $response->redirect_url;
    } catch (\Exception $e) {
        dd([
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
        ]);
    }

    return redirect($paymentUrl);
}

    public function success(Request $request)
    {
        $transaction = $this->transactionRepository->getTransactionByCode($request->order_id);

        if (!$transaction) {
            return redirect()->route('home');
        }

        return view('pages.booking.success', compact('transaction'));
    }
    
    public function checkBooking() 
    {
        return view('pages.booking.check-booking');
    }

    public function show(BookingShowRequest $request) 
    {
        $transaction = $this->transactionRepository->getTransactionByCodePhone($request->code, $request->phone);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Data Transaksi Tidak Ditemukan');
        }

        return view('pages.booking.detail', compact('transaction'));
    }
}
