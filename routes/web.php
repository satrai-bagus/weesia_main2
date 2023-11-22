<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberRoutesController;
use App\Http\Controllers\RekapanController;
use App\Models\Rekapan;
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

// Route::get('/', function () {
//     return view('auth.login');
// })->name('login');

// Route::fallback(function() {
//     $prevUrl = url()->previous();
    
//     if($prevUrl == 'http://127.0.0.1:8000/not-found') {
//         $prevUrl = '/';
//     }

//     return view('auth.404', [
//         'prevUrl' => $prevUrl,
//     ]);
// })->name('404');

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'loginForm');
    Route::post('/auth/login', 'login');
    Route::get('/auth/logout', 'logout');
    
    Route::get('/register', 'regisForm');
    Route::post('/auth/register', 'register');
});

// MEMBER
Route::middleware('auth')->prefix('/member')->group(function () {

    Route::controller(MemberRoutesController::class)->group(function () {
        Route::middleware('level:admin,member,elder')->group(function () {
            Route::get('/', 'membersHome');
            Route::get('/kontak', 'membersContact');
        });
    
        Route::middleware('level:admin,member')->prefix('/tutorial')->group(function () {
            Route::get('/', 'membersTutorial');
        
            Route::get('/clone-line', 'tutorialsLine');
            Route::get('/relieve-aplikasi-parallel', 'tutorialsRelieveParallel');
            Route::get('/relieve-bot-line', 'tutorialRelieveLine');
        });
    });

    Route::controller(AplikasiController::class)->prefix('/aplikasi')->group(function () {
        Route::get('/', 'readAplikasi')->middleware('level:admin,member,elder');
        
        Route::middleware('level:admin')->group(function () {
            Route::post('/creating', 'createAplikasi');
            Route::get('/create', 'createForm');
            
            Route::put('/updating/{id}', 'updateAplikasi');
            Route::get('/update/{id}', 'updateForm');
            
            Route::delete('/delete/{id}', 'deleteAplikasi');
        });
    });
    
    Route::controller(RekapanController::class)->prefix('/rekapan')->group(function () {
        Route::get('/', 'readRekapan')->middleware('level:admin,member,elder');
        
        Route::middleware('level:admin')->group(function () {
            Route::post('/creating', 'createRekapan');
            Route::get('/create', 'createForm');
            
            Route::put('/updating/{id}', 'updateRekapan');
            Route::get('/update/{id}', 'updateForm');
            
            Route::delete('/delete/{id}', 'deleteRekapan');
        });
    });

    Route::controller(AbsenController::class)->prefix('/absen')->group(function () {
        Route::get('/test', 'getTaskDesc');
        
        Route::get('/', 'absenHome');
        Route::post('/add/{id}', 'absenAdd');
    });
});
// END MEMBER

Route::middleware('level:admin')->prefix('/dashboard')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/absen', 'toDashboard')->name('dashboard.absen');
        Route::get('/absen/export', 'exportAbsen')->name('dashboard.absen.export');


        Route::get('/member', 'toDashboardMember')->name('dashboard.member');
        
        Route::get('/member/demote/{id}', 'dashboardMemberDemoted')->name('dashboard.member.demote');
        Route::get('/member/banned/{id}', 'dashboardMemberDelete')->name('dashboard.member.banned');
        Route::get('/member/kick/{id}', 'dashboardMemberDelete')->name('dashboard.member.kick');

        Route::get('/member/change/role/', 'dashboardMemberRole')->name('dashboard.member.set.role');
        Route::get('/member/update/token/', 'dashboardMemberToken')->name('dashboard.member.set.token');
    });
});
