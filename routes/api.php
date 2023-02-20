<?php

use App\Http\Controllers\api\admin\PesanApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [App\Http\Controllers\api\AuthApiController::class, 'login']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    //Pesan
    Route::prefix('pesan')->controller(PesanApiController::class)->group(function () {
        // Route::get('/', 'indexMasuk');
        // Route::post('/update/{id}', 'ubah_status');

        Route::get('/terkirim', 'pesan_terkirim');
        Route::post('/store', 'kirim_pesan');
    });
});

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    
});

