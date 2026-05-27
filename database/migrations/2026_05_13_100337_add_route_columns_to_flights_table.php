<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->foreignId('departure_airport_id')->nullable()->constrained('airports')->cascadeOnDelete();
            $table->foreignId('arrival_airport_id')->nullable()->constrained('airports')->cascadeOnDelete();
            $table->dateTime('departure_time')->nullable();
             $table->dateTime('arrival_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropForeign(['departure_airport_id']);
            $table->dropForeign(['arrival_airport_id']);
            $table->dropColumn(['departure_airport_id', 'arrival_airport_id', 'departure_time', 'arrival_time']);
        });
    }
};
