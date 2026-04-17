<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VrpController;

Route::post('/vrp', [VrpController::class, 'solve']);