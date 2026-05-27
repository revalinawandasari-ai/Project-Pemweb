<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Flight;

class FlightSeatsSeeder extends Seeder
{
    public function run(): void
    {
        $flights = Flight::all();

        foreach ($flights as $flight) {
        // Economy seats (A-F, 6 kolom x 5 baris = 30 seats)
            $rowNum = 1;
            foreach (range('A', 'H') as $col) {
                for ($c = 1; $c <= 6; $c++) { 
                    DB::table('flight_seats')->insert([
                        'flight_id'    => $flight->id,
                        'name'         => $col . $c,
                        'row'          => $rowNum,
                        'column'       => $c,
                        'class_type'   => 'economy',
                        'is_available' => 1,
                        'deleted_at'   => null,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
                $rowNum++;
            }

            // Business seats (A-D, 4 kolom x 4 baris = 16 seats)
            $rowNum = 1;
            foreach (range('A', 'G') as $col) {
                for ($c = 1; $c <= 4; $c++) {
                    DB::table('flight_seats')->insert([
                        'flight_id'    => $flight->id,
                        'name'         => $col . $c,
                        'row'          => $rowNum,
                        'column'       => $c,
                        'class_type'   => 'business',
                        'is_available' => 1,
                        'deleted_at'   => null,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
                $rowNum++;
            }
        }
    }
}