<?php

use App\Http\Controllers\SuperAdmin\MonitorController;
use Illuminate\Support\Facades\Route;

// Menu Monitor
Route::get('/monitor', [MonitorController::class,'monitor'])->name('monitor.get');
Route::post('/monitor/add/bpjs', [MonitorController::class,'monitor_bpjs'])->name('monitor.add.bpjs');
Route::post('/monitor/add/nobpjs', [MonitorController::class,'monitor_nobpjs'])->name('monitor.add.nobpjs');
Route::get('/monitor/loket-antrian', [MonitorController::class, 'loketAntrian'])->name('monitor.loket.antrian');
