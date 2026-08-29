<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();

        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'degraded',
            'database' => 'disconnected',
        ], 503);
    }
});

Route::post('/register', [UserController::class, 'register'])
    ->middleware('throttle:register');
