<?php

use App\Http\Controllers\EstimateDeliveryTimeController;
use App\Http\Controllers\IndexVendorsDelayController;
use App\Http\Controllers\ReportDelayController;
use App\Http\Controllers\ShowAgentAssignedDelayReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendors')->name('vendors.')->group(function () {
    Route::get('delays', IndexVendorsDelayController::class)->name('delays');
});

Route::prefix('orders')->name('orders.')->group(function () {
    Route::prefix('{order}')->group(function () {
        Route::get('eta-mock', EstimateDeliveryTimeController::class)->name('eta-mock');

        Route::post('report-delay', ReportDelayController::class)
            ->can('reportDelay', 'order')
            ->name('report-delay');
    });
});

Route::prefix('agents')->name('agents.')->group(function () {
    Route::prefix('{agent}')->group(function () {
        Route::get('delay-report', ShowAgentAssignedDelayReportController::class)->name('delay-report');
    });
});
