<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('time_slot');
            $table->string('space_name');
            $table->json('games')->nullable();
            $table->json('coffees')->nullable();
            $table->string('customer_id')->nullable();
            $table->decimal('total_amount', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['date', 'time_slot', 'space_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};