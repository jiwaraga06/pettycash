<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\PettyCashDetailController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/login', 'login')->name('login');
});


Route::middleware('auth')->group(function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::post('/chartSummaryAmount', 'chartSummaryAmount')->name('chartSummaryAmount');
        // Route::get('/home', 'dashboard'); // Gunakan nama yang sama, atau kalau tidak perlu, hapus salah satunya
    });
    Route::prefix('account')->controller(AccountController::class)->group(function () {
        Route::get('/', 'showAcount')->name('showAcount');
        Route::post('/addAccount', 'addAccount')->name('addAccount');
        Route::put('/editAccount/{id}', 'editAccount')->name('editAccount');
        Route::delete('/{id}}', 'deleteAccount')->name('deleteAccount');
    });
    Route::prefix('role')->controller(RoleController::class)->group(function () {
        Route::get('/', 'showRole')->name('showRole');
        Route::post('/addRole', 'addRole')->name('addRole');
        Route::put('/editRole/{id}', 'editRole')->name('editRole');
        Route::delete('/{id}', 'deleteRole')->name('deleteRole');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('pettycash')->controller(PettyCashController::class)->group(function () {
        Route::get('/', 'showPettyCashUser')->name('show.showPettyCashUser');
        Route::get('/request', 'showRequestPettyCash')->name('show.showRequestPettyCash');
        Route::get('/edit/{id}', 'showEditPettyCash')->name('show.showEditPettyCash');
        Route::get('/Dept', 'showPettyCashDeptHead')->name('show.showPettyCashDeptHead');
        Route::get('/DeptFin', 'showPettyCashFinHead')->name('show.showPettyCashFinHead');
        Route::post('/addRequest', 'addRequest')->name('addRequest');
        Route::put('/editPettyCash/{id}', 'editPettyCash')->name('editPettyCash');
        Route::post('/{id}', 'deletePettyCash')->name('deletePettyCash');
        // dept head
        Route::post('/approvedDeptHead/{id}', 'approvedDeptHead')->name('approvedDeptHead');
        Route::post('/rejectedDeptHead/{id}', 'rejectedDeptHead')->name('rejectedDeptHead');
        // finance
        Route::post('/approvedFinance/{id}', 'approvedFinance')->name('approvedFinance');
        Route::post('/rejectedFinance/{id}', 'rejectedFinance')->name('rejectedFinance');
        Route::put('/paid/{id}', 'paid')->name('paid');
        // REKAP
        Route::get('/RekapPermohonan', 'showRekapPermohonan')->name('show.showRekapPermohonan');
        Route::get('/RekapRincian', 'showRekapRincian')->name('show.showRekapRincian');
        Route::get('/exportRekapPermohonan', 'exportRekapPermohonan')->name('exportRekapPermohonan');
        Route::get('/exportRekapRincian', 'exportRekapRincian')->name('exportRekapRincian');
        // SEND EMAIL
        Route::get('/sendEmail', 'sendEmail');
    });

    Route::prefix('detailPettyCash')->controller(PettyCashDetailController::class)->group(function () {
        Route::get('/{id}', 'showDetailPettyCash')->name('show.showDetailPettyCash');
        Route::post('/addDetail/{id}', 'addDetail')->name('addDetail');
        Route::put('/editDetail/{id}', 'editDetail')->name('editDetail');
        Route::delete('/deleteDetail/{id}', 'deleteDetail')->name('deleteDetail');
    });
});
