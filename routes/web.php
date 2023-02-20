<?php

use App\Http\Controllers\Admin\IdentitasController;
use App\Http\Controllers\admin\masterdata\AdminController;
use App\Http\Controllers\admin\masterdata\AnggotaController;
use App\Http\Controllers\admin\masterdata\BukuController;
use App\Http\Controllers\admin\masterdata\DataPeminjamanController;
use App\Http\Controllers\admin\masterdata\KategoriController;
use App\Http\Controllers\admin\masterdata\PenerbitController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\user\PeminjamanController;
use App\Http\Controllers\User\PengembalianController;
use App\Http\Controllers\user\PesanController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\UserRegisterController;
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

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

//tidak bisa kembali ke login dengan cara hapus url
Route::get('/home', function () {
    if (Auth::user()->role == 'admin') {

        return redirect()->route('admin.dashboard');
    }
    if (Auth::user()->role == 'user') {

        return redirect()->route('user.dashboard');
    }
})->middleware('auth');

Route::post('/register', [UserRegisterController::class, 'userRegister'])->name('user.register');

//ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('/admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    //MasterData 
    //Anggota
    Route::get('anggota', [AnggotaController::class, 'index_anggota'])->name('admin.anggota');
    Route::post('tambah_anggota', [AnggotaController::class, 'tambahAnggota'])->name('admin.tambah_anggota');
    Route::put('/edit/anggota/{id}', [AnggotaController::class, 'ubahAnggota'])->name('admin.update.anggota');
    Route::delete('/hapus/anggota/{id}', [AnggotaController::class, 'hapusAnggota']);
    Route::put('/update_status/{id}', [AnggotaController::class, 'updateStatus'])->name('admin.update_status');

    //Penerbit
    Route::get('penerbit', [PenerbitController::class, 'index_penerbit'])->name('admin.penerbit');
    Route::post('tambah_penerbit', [PenerbitController::class, 'tambah_penerbit'])->name('admin.tambah_penerbit');
    Route::put('ubah_penerbit/{id}', [PenerbitController::class, 'ubah_penerbit'])->name('admin.update_penerbit');
    Route::post('ubah_status/{id}', [PenerbitController::class, 'ubah_status'])->name('admin.update_status_penerbit');
    Route::delete('/hapus/penerbit/{id}', [PenerbitController::class, 'hapus'])->name('admin.hapus_penerbit');

    //Admin
    Route::get('admin', [AdminController::class, 'indexAdmin'])->name('admin.administrator');
    Route::post('/tambah-admin', [AdminController::class, 'storeAdministrator'])->name('admin.tambah_admin');
    Route::put('/edit/admin/{id}', [AdminController::class, 'updateAdmin'])->name('admin.update_admin');
    Route::delete('/hapus/admin/{id}', [AdminController::class, 'deleteAdmin']);

    //Peminjaman
    Route::get('peminjaman', [DataPeminjamanController::class, 'indexPeminjaman'])->name('admin.peminjaman');

    //Buku
    Route::get('/buku', [BukuController::class, 'indexBuku'])->name('admin.buku');
    Route::post('/tambah-buku', [BukuController::class, 'storeBuku'])->name('admin.tambah_buku');
    Route::put('/edit/buku/{id}', [BukuController::class, 'updateBuku'])->name('admin.update.buku');
    Route::delete('/hapus/buku/{id}', [BukuController::class, 'deleteBuku']);

    //Kategori
    Route::get('/kategori', [KategoriController::class, 'indexKategori'])->name('admin.kategori');
    Route::post('/tambah-kategori', [KategoriController::class, 'storeKategori'])->name('admin.tambah_kategori');
    Route::put('/edit/kategori/{id}', [KategoriController::class, 'updateKategori'])->name('admin.update_kategori');
    Route::delete('/hapus/kategori/{id}', [KategoriController::class, 'deleteKategori']);

    //Identitas 
    Route::get('/identitas', [IdentitasController::class, 'index_identitas'])->name('admin.identitas');
    Route::put('/edit/identitas', [IdentitasController::class, 'ubah_identitas'])->name('admin.update_identitas');
});

//USER
Route::middleware(['auth', 'role:user'])->prefix('/user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    //Peminjaman 
    Route::get('riwayat-peminjaman', [PeminjamanController::class, 'riwayatPeminjaman'])->name('user.riwayat_peminjaman');

    Route::get('form-peminjaman', [PeminjamanController::class, 'formPeminjaman'])->name('user.form_peminjaman');
    Route::post('tambah-peminjaman', [PeminjamanController::class, 'tambahPeminjaman'])->name('user.tambah_peminjaman');

    //Pengembalian
    Route::get('riwayat-pengembalian', [PengembalianController::class, 'riwayatPengembalian'])->name('user.riwayat_pengembalian');

    Route::get('form-pengembalian', [PengembalianController::class, 'formPengembalian'])->name('user.form_pengembalian');
    Route::post('tambah-pengembalian', [PengembalianController::class, 'submit_pengembalian'])->name('user.submit_pengembalian');

    //Profile
    Route::get('profile', [ProfileController::class, 'profile'])->name('user.profile');
    Route::put('gambar', [ProfileController::class, 'gambar'])->name('user.gambar');

    //Pesan
    Route::get('pesan-masuk', [PesanController::class, 'pesan_masuk'])->name('user.pesan_masuk');
    Route::post('ubah_status', [PesanController::class, 'ubah_status'])->name('user.ubah_status');

    Route::get('kirim-pesan', [PesanController::class, 'kirim'])->name('user.kirim_pesan');
    // Route::post('pesan_terkirim', [PesanController::class, 'pesan_terkirim'])->name('user.pesan_terkirim');
    Route::post('/kirim-pesan', [PesanController::class, 'kirim_pesan'])->name('user.kirim_pesan');
});
