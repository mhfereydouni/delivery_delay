<?php

use App\Enums\TripStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->enum('status', TripStatus::allValues());
            $table->timestamps();

            $table->unique(['order_id', 'status']);

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};
