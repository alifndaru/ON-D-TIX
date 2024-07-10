<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/pengaturan', [App\Http\Controllers\UserController::class, 'create'])->name('pengaturan');
    Route::post('/edit/name', [App\Http\Controllers\UserController::class, 'name'])->name('edit.name');
    Route::post('/edit/password', [App\Http\Controllers\UserController::class, 'password'])->name('edit.password');
    Route::get('/transaksi/{order_id}', [App\Http\Controllers\LaporanController::class, 'show'])->name('transaksi.show');

    Route::middleware(['petugas'])->group(function () {
        Route::get('/pembayaran/{id}', [App\Http\Controllers\LaporanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/petugas', [App\Http\Controllers\LaporanController::class, 'petugas'])->name('petugas');
        Route::post('/petugas', [App\Http\Controllers\LaporanController::class, 'kode'])->name('petugas.kode');

        Route::middleware(['admin'])->group(function () {
            Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
            Route::resource('/category', App\Http\Controllers\CategoryController::class);
            Route::resource('/transportasi', App\Http\Controllers\TransportasiController::class);
            Route::resource('/rute', App\Http\Controllers\RuteController::class);
            Route::resource('/user', App\Http\Controllers\UserController::class);
            Route::get('/transaksi', [App\Http\Controllers\LaporanController::class, 'index'])->name('transaksi');
            Route::resource('/kelas', App\Http\Controllers\KelasController::class);
            Route::get('/create-rute', [App\Http\Controllers\RuteController::class, 'create'])->name('create-rute');
            Route::get('/detail/admin/{order_id}', [App\Http\Controllers\PaymentController::class, 'detailTicket'])->name('detailTicketAdmin');
            Route::get('/pendapatan-data', [HomeController::class, 'getPendapatanData']);
        });
    });

    Route::middleware(['penumpang'])->group(function () {
        Route::get('/detail/{order_id}', [App\Http\Controllers\PaymentController::class, 'detailTicket'])->name('detailTicket');
        Route::post('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
        Route::get('/pilih-kursi/{id}', [App\Http\Controllers\HomepageController::class, 'kursi'])->name('pilih-kursi');
        Route::get('/history', [App\Http\Controllers\PaymentController::class, 'history'])->name('history');
        Route::get('/pesan/{kursi}/{data}', [App\Http\Controllers\PemesananController::class, 'pesan'])->name('pesan');
        Route::get('/cari/kursi/{data}', [App\Http\Controllers\PemesananController::class, 'edit'])->name('cari.kursi');
        Route::get('/kategori/{slug}', [App\Http\Controllers\HomepageController::class, 'show'])->name('category.show');
        Route::get('cetak/{order_id}', [App\Http\Controllers\PaymentController::class, 'cetakDetail'])->name('cetakTiket');
        Route::get('checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
        Route::post('/payments/{orderId}/cancel', [App\Http\Controllers\PaymentController::class, 'cancelPayment'])->name('cencelled-payment');
    });
});
