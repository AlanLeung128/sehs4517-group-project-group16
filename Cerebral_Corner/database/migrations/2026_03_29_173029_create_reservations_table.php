<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('date');                    
            $table->string('time_slot');             
            $table->string('space_name');            
            $table->string('game')->nullable();           
            $table->json('coffees')->nullable();     
            $table->string('phone')->nullable();     
            $table->foreignId('customer_id')->nullable()->constrained('members');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('confirmed'); 
            $table->timestamps();


            $table->index(['date', 'space_name', 'time_slot']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};

