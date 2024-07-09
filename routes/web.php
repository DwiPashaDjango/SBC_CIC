<?php

use App\Http\Controllers\Admin\JadwaPenjualanController;
use App\Http\Controllers\Admin\ShowLaporanController;
use App\Http\Controllers\Admin\StandController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\LaporanController;
use App\Http\Controllers\Mahasiswa\PengajuanJadwalController;
use App\Http\Controllers\Mahasiswa\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/auth', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(['auth']);

Route::get('/callendar-jadwals', [DashboardController::class, 'fetchJadwal'])->name('fetchJadwal');

// register
Route::get('/register-sbc', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['role:Admin|Mahasiswa']);
    Route::post('/getChart', [DashboardController::class, 'getJadwalPengajuan'])->name('getJadwalPengajuan')->middleware(['role:Admin']);

    Route::prefix('admin')->group(function () {
        Route::prefix('jadwals')->group(function () {
            Route::get('/list', [JadwaPenjualanController::class, 'index'])->name('admin.jadwal.index')->middleware(['role:Admin']);
            Route::get('/list/repeat', [JadwaPenjualanController::class, 'getJadwalNotDate'])->name('admin.jadwal.getJadwalNotDate')->middleware(['role:Admin']);
            Route::get('/list/{id}', [JadwaPenjualanController::class, 'show'])->name('admin.jadwal.show')->middleware(['role:Admin']);
            Route::post('/sendVerification', [JadwaPenjualanController::class, 'sendVerification'])->name('admin.jadwal.sendVerification')->middleware(['role:Admin']);
            Route::post('/switch', [JadwaPenjualanController::class, 'switchPenjualan'])->name('admin.jadwal.switchPenjualan')->middleware(['role:Admin']);
            Route::get('/stands', [JadwaPenjualanController::class, 'getStand'])->name('admin.jadwal.getStand')->middleware(['role:Admin']);
            Route::put('/update/stand/{id}', [JadwaPenjualanController::class, 'setStands'])->name('admin.jadwal.setStands')->middleware(['role:Admin']);
        });

        Route::prefix('/stands')->group(function () {
            Route::get('/', [StandController::class, 'index'])->name('admin.stand.index')->middleware(['role:Admin']);
            Route::get('/show/{id}', [StandController::class, 'show'])->name('admin.stand.show')->middleware(['role:Admin']);
            Route::post('/store', [StandController::class, 'store'])->name('admin.stand.store')->middleware(['role:Admin']);
            Route::put('/update/{id}', [StandController::class, 'update'])->name('admin.stand.update')->middleware(['role:Admin']);
            Route::delete('/delete/{id}', [StandController::class, 'destroy'])->name('admin.stand.delete')->middleware(['role:Admin']);
        });

        Route::prefix('/reports')->group(function () {
            Route::get('/', [ShowLaporanController::class, 'index'])->name('admin.laporan.index')->middleware(['role:Admin']);
            Route::get('/{id}/show', [ShowLaporanController::class, 'show'])->name('admin.laporan.show')->middleware(['role:Admin']);
            Route::get('/{id}/pdf', [ShowLaporanController::class, 'generatePdf'])->name('admin.laporan.generatePdf')->middleware(['role:Admin']);
        });
    });

    Route::prefix('mahasiswa')->group(function () {
        Route::get('/jadwals', [PengajuanJadwalController::class, 'index'])->name('mhs.jadwals.index')->middleware(['role:Mahasiswa']);
        Route::get('/jadwals/{id}', [PengajuanJadwalController::class, 'show'])->name('mhs.jadwals.show')->middleware(['role:Mahasiswa']);
        Route::post('/check/jadwal', [PengajuanJadwalController::class, 'getJadwalByTgl'])->name('mhs.chcek.jadwal')->middleware(['role:Mahasiswa']);
        Route::post('/jadwals/save', [PengajuanJadwalController::class, 'store'])->name('mhs.save.jadwal')->middleware(['role:Mahasiswa']);
        Route::put('/jadwals/update/{id}', [PengajuanJadwalController::class, 'update'])->name('mhs.jadwal.update')->middleware(['role:Mahasiswa']);
        Route::put('/jadwals/accepted/{id}', [PengajuanJadwalController::class, 'accepted'])->name('mhs.jadwal.accepted')->middleware(['role:Mahasiswa']);
        Route::put('/jadwals/rejected/{id}', [PengajuanJadwalController::class, 'rejected'])->name('mhs.jadwal.rejected')->middleware(['role:Mahasiswa']);
        Route::delete('/jadwals/destroy/{id}', [PengajuanJadwalController::class, 'destroy'])->name('mhs.jadwal.update')->middleware(['role:Mahasiswa']);

        Route::prefix('products')->group(function () {
            Route::get('/list', [ProductController::class, 'index'])->name('mhs.product.index')->middleware(['role:Mahasiswa']);
            Route::get('/create', [ProductController::class, 'create'])->name('mhs.product.create')->middleware(['role:Mahasiswa']);
            Route::post('/store', [ProductController::class, 'store'])->name('mhs.product.store')->middleware(['role:Mahasiswa']);
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('mhs.product.edit')->middleware(['role:Mahasiswa']);
            Route::put('/{id}/update', [ProductController::class, 'update'])->name('mhs.product.update')->middleware(['role:Mahasiswa']);
            Route::delete('/destroy/{id}', [ProductController::class, 'destroy'])->name('mhs.product.destroy')->middleware(['role:Mahasiswa']);
        });

        Route::prefix('laporans')->group(function () {
            Route::get('/list', [LaporanController::class, 'index'])->name('mhs.laporan.index')->middleware(['role:Mahasiswa']);
            Route::post('/store', [LaporanController::class, 'store'])->name('mhs.laporan.store')->middleware(['role:Mahasiswa']);
            Route::post('/getProducts', [LaporanController::class, 'getProducts'])->name('mhs.laporan.getProducts')->middleware(['role:Mahasiswa']);
            Route::delete('/destroy/{id}', [LaporanController::class, 'destroy'])->name('mhs.laporan.destroy')->middleware(['role:Mahasiswa']);
            Route::get('/pdf', [LaporanController::class, 'generatePdf'])->name('mhs.laporan.generatePdf')->middleware(['role:Mahasiswa']);
        });
    });
});
