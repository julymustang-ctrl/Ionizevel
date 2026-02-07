<?php

use Illuminate\Support\Facades\Route;
use Modules\StaticBlock\Http\Controllers\StaticBlockController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('staticblocks', StaticBlockController::class)->names('staticblock');
});
