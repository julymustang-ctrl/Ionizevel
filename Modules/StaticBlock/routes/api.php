<?php

use Illuminate\Support\Facades\Route;
use Modules\StaticBlock\Http\Controllers\StaticBlockController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('staticblocks', StaticBlockController::class)->names('staticblock');
});
