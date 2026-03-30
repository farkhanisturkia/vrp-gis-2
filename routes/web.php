<?php

use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\CoordinateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/armadas', [ArmadaController::class, 'index'])->name('armadas.index');
        Route::post('/armadas', [ArmadaController::class, 'store'])->name('armadas.store');
        Route::put('/armadas/{armada}', [ArmadaController::class, 'update'])->name('armadas.update');
        Route::delete('/armadas/{armada}', [ArmadaController::class, 'destroy'])->name('armadas.destroy');

        Route::get('/coordinates', [CoordinateController::class, 'index'])->name('coordinates.index');
        Route::post('/coordinates', [CoordinateController::class, 'store'])->name('coordinates.store');
        Route::put('/coordinates/{coordinate}', [CoordinateController::class, 'update'])->name('coordinates.update');
        Route::delete('/coordinates/{coordinate}', [CoordinateController::class, 'destroy'])->name('coordinates.destroy');

        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('orders.view');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

require __DIR__.'/auth.php';
