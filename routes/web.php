<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\RouteStopController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin-panel', function () {
    return view('admin_panel');
});


Route::get('/api/find-bus', [BusController::class, 'findBus']);

Route::get('/routes', [RouteController::class, 'index']);
Route::get('/routes/{id}', [RouteController::class, 'show']);
Route::post('/routes', [RouteController::class, 'store']);
Route::put('/routes/{id}', [RouteController::class, 'update']);
Route::delete('/routes/{id}', [RouteController::class, 'destroy']);

Route::get('/stops', [StopController::class, 'index']);
Route::get('/stops/{id}', [StopController::class, 'show']);
Route::post('/stops', [StopController::class, 'store']);
Route::put('/stops/{id}', [StopController::class, 'update']);
Route::delete('/stops/{id}', [StopController::class, 'destroy']);

Route::get('/schedule', [ScheduleController::class, 'index']);
Route::get('/schedule/{id}', [ScheduleController::class, 'show']);
Route::post('/schedule', [ScheduleController::class, 'store']);
Route::put('/schedule/{id}', [ScheduleController::class, 'update']);
Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy']);

Route::post('/route-stops', [RouteStopController::class, 'store']);
Route::get('/route-stops/{route_id}', [RouteStopController::class, 'index']);
