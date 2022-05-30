<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\HasilLatihanController;


Route::get('/test', function(){
    return response()->json([
        'msg' => 'success'
    ]);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware(['jwt.verify'])->group(function () {
   Route::get('/user', [UserController::class, 'getCurrentUser']);
    Route::post('/update', [UserController::class, 'update']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::get('/paket/get-data', [PaketController::class, 'apiGetData']);
    Route::get('/soal/get-data/{idPaket}', [SoalController::class, 'apiGetData']);
    Route::post('/soal/hasil-latihan/simpan', [SoalController::class, 'apiSimpanHasilLatihan']);
    Route::get('/hasil-latihan/get-data', [HasilLatihanController::class, 'apiGetData']);
});
