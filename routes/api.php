<?php

use App\Http\Controllers\EstimateDeliveryTimeController;
use App\Http\Controllers\ReportDelayController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->name('orders.')->group(function () {
    Route::prefix('{order}')->group(function () {
        Route::get('eta-mock', EstimateDeliveryTimeController::class)->name('eta-mock');

        Route::post('report-delay', ReportDelayController::class)
            ->can('reportDelay', 'order')
            ->name('report-delay');
    });
});
