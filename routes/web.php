<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\SoalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/blank', function () {
    return view('blank');
});

Route::get('/privacypolice', function () {
    return view('privacypolice');
});

// Start Auth Custom
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/post-login', [AuthController::class, 'postLogin'])->name('post.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// End Auth Custom

Route::group(['middleware' => ['auth', 'cekRole:admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Paket
    Route::get('/paket', [PaketController::class, 'index'])->name('paket');
    Route::get('/paket/get-data', [PaketController::class, 'getData'])->name('paket.get_data');
    Route::post('paket/store', [PaketController::class, 'store'])->name('paket.store');
    Route::post('paket/update', [PaketController::class, 'update'])->name('paket.update');
    Route::post('paket/delete', [PaketController::class, 'delete'])->name('paket.delete');
    // End Paket

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    Route::get('/get-data', [KategoriController::class, 'getData'])->name('kategori.get_data');
    Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::post('/update', [KategoriController::class, 'update'])->name('kategori.update');
    Route::post('/delete', [KategoriController::class, 'delete'])->name('kategori.delete');
    // End Kategori

    // Soal
    Route::get('/soal', [SoalController::class, 'index'])->name('soal');
    Route::get('/soal/get-data', [SoalController::class, 'getData'])->name('soal.get_data');
    Route::get('/soal/create', [SoalController::class, 'createSoal'])->name('soal.create');
    Route::get('/soal/create/pilih-kategori', [SoalController::class, 'pilihKategori'])->name('soal.pilih_kategori');
    Route::post('/soal/store', [SoalController::class, 'storeSoal'])->name('soal.store');
    Route::get('/soal/jawaban', [SoalController::class, 'jawabanSoal'])->name('soal.jawaban');
    Route::get('/soal/edit/{id}', [SoalController::class, 'editSoal'])->name('soal.edit');
    Route::post('/soal/update', [SoalController::class, 'updateSoal'])->name('soal.update');
    Route::post('/soal/delete', [SoalController::class, 'deleteSoal'])->name('soal.delete');
    // End Soal
});
