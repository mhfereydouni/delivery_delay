<?php

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
            $table->enum('status', ['ASSIGNED', 'AT_VENDOR', 'PICKED', 'DELIVERED']);
            $table->timestamps();

            $table->unique(['order_id', 'status']);

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};
