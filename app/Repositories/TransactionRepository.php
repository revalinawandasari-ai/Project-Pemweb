<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Jobs\SendMailSuccessJob;
use App\Models\Transaction;
use App\Models\TransactionPassenger;
use App\Models\FlightClass;
use App\Models\PromoCode;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionDataFromSession()
    {
        return session()->get('transaction', []);
    }

    public function saveTransactionDataToSession($data)
    {
        $transaction = session()->get('transaction', []);

        foreach ($data as $key => $value) {
            if ($key === 'selected_seats' && is_string($value)) {
                $value = json_decode($value, true);
            }
            $transaction[$key] = $value;
        }

        session()->put('transaction', $transaction);
    }

    public function saveTransaction($data)
    {
        $data['code'] = $this->generateTransactionCode();
        $data['number_of_passengers'] = $this->countPassengers($data['passenger']);

        // Hitung subtotal dan grand total awal
        $data['subtotal'] = $this->calculateSubtotal($data['flight_class_id'], $data['number_of_passengers']);
        $data['grandtotal'] = $data['subtotal'];

        // Terapkan promo jika ada
        if (!empty($data['promo_code'])) {
            $data = $this->applyPromoCode($data);
        }

        // Tambahkan PPN
        $data['grandtotal'] = $this->addPPN($data['grandtotal']);

        // Simpan transaksi dan penumpang
        $transaction = $this->createTransaction($data);
        $this->savePassengers($data['passenger'], $transaction->id);

        session()->forget('transaction');

        return $transaction;
    }

    private function generateTransactionCode()
    {
        return 'BWAGARUDA' . rand(1000, 9999);
    }

    private function countPassengers($passengers)
    {
        return count($passengers);
    }

    private function calculateSubtotal($flightClassId, $numberOfPassengers)
    {
        $price = FlightClass::findOrFail($flightClassId)->price;
        return $price * $numberOfPassengers;
    }

    private function applyPromoCode($data)
    {
        $promo = PromoCode::where('code', $data['promo_code'])
            ->where('valid_until', '>=', now())
            ->where('is_used', false)
            ->first();

        if ($promo) {
            if ($promo->discount_type === 'percentage') {
                $data['discount'] = $data['grandtotal'] * ($promo->discount / 100);
            } else {
                $data['discount'] = $promo->discount;
            }

            $data['grandtotal'] -= $data['discount'];
            $data['promo_code_id'] = $promo->id;

            // Tandai promo code sebagai sudah digunakan
            $promo->update(['is_used' => true]);
        }

        return $data;
    }

    private function addPPN($grandTotal)
    {
        $ppn = $grandTotal * 0.11;
        return (int) round($grandTotal + $ppn);
    }

    private function createTransaction($data)
    {
        $contact = $data['passenger'][0] ?? [];

        return Transaction::create([
            'code'                 => $data['code'],
            'flight_id'            => $data['flight_id'],
            'flight_class_id'      => $data['flight_class_id'],
            'name'                 => $data['name']  ?? $contact['name']  ?? null, // ← dari root session
            'email'                => $data['email'] ?? null,                       // ← dari root session
            'phone'                => $data['phone'] ?? null,                       // ← dari root session
            'number_of_passengers' => $data['number_of_passengers'],
            'promo_code_id'        => $data['promo_code_id'] ?? null,
            'payment_status'       => 'pending',
            'subtotal'             => (int) $data['subtotal'],
            'grandtotal'           => (int) $data['grandtotal'],
        ]);
    }

    private function savePassengers($passengers, $transactionId)
    {
        foreach ($passengers as $passenger) {
            TransactionPassenger::create([
                'transaction_id'  => $transactionId,
                'flight_seat_id'  => isset($passenger['flight_seat_id']) ? (int) $passenger['flight_seat_id'] :  null,
                'name'            => $passenger['name'] ?? null,
                'date_of_birth'   => $passenger['date_of_birth'] ?? null,
                'nationality'     => $passenger['nationality'] ?? null, 
            ]);
        }
    }

    public function getTransactionByCode($code)
    {
        return Transaction::where('code', $code)->first();
    }

    public function getTransactionByCodePhone($code,  $phone)
    {
        return Transaction::where('code', $code)->where('phone', $phone)->first();
    }
}