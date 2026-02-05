<?php

use App\Http\Livewire\Ace\MaterialInput as AceMaterialInput;
use App\Http\Livewire\Ace\MaterialLadleTransfer;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Invetory\StockForm;
use App\Http\Livewire\Invetory\StockTable;
use App\Http\Livewire\Jsh\MaterialInput;
use App\Http\Livewire\Report\Jsh\TotalFurnaceJsh;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    // Route::prefix('/')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::prefix('jsh')->name('jsh.')->group(function () {
        Route::get('/material-input', MaterialInput::class)->name('material-input.index');
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/total-furnace', TotalFurnaceJsh::class)->name('total-furnace-report');
        });
    });

    Route::prefix('ace')->name('ace.')->group(function () {
        Route::get('/material-input', AceMaterialInput::class)->name('material-input-ace.index');
        Route::get('/ladle-transfer', MaterialLadleTransfer::class)->name('ladle-transfer.index');
        Route::prefix('report')->group(function () {
            Route::get('/total-furnace',);
        });
    });
    // });
});

require __DIR__ . '/auth.php';
