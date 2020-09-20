<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OperasiMahasiswa\MahasiswaController;

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

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\Auth',
], function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group([
        'middleware' => 'auth:api',
    ], function(){
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('getuserlogin', [AuthController::class, 'getuserlogin']);
    });
});

Route::group([
    'prefix' => 'operasimahasiswa',
    'namespace' => 'App\Http\Controllers\OperasiMahasiswa',
    'middleware' => 'auth:api',
], function(){
    Route::post('createmahasiswa', [MahasiswaController::class, 'create']);
});