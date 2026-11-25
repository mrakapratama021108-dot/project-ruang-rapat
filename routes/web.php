<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Alur Kiri: Booking Ruangan
Route::get('/booking/buat', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking/simpan', [BookingController::class, 'store'])->name('booking.store');

// Alur Kanan: Riwayat & Laporan
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
Route::get('/riwayat/cetak', [RiwayatController::class, 'cetak'])->name('riwayat.cetak');
Route::get('/riwayat/{booking}', [RiwayatController::class, 'show'])->name('riwayat.show');
Route::delete('/riwayat/{booking}/batal', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');