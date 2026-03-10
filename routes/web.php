<?php

use App\Http\Livewire\Ace\MaterialInput as AceMaterialInput;
use App\Http\Livewire\Ace\MaterialAdjust as AceMaterialAdjust;
use App\Http\Livewire\Ace\MaterialLadleTransfer;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Invetory\StockForm;
use App\Http\Livewire\Invetory\StockTable;
use App\Http\Livewire\Jsh\MaterialInput;
use App\Http\Livewire\Jsh\MaterialAdjust as JshMaterialAdjust;
use App\Http\Livewire\Report\Ace\AllFurnaceAce;
use App\Http\Livewire\Report\Ace\FurnaceAce;
use App\Http\Livewire\Report\Ace\MaterialAdjustReportAce;
use App\Http\Livewire\Report\Ace\ProductAce;
use App\Http\Livewire\Report\Jsh\AllFurnaceJsh;
use App\Http\Livewire\Report\Jsh\FurnaceJsh;
use App\Http\Livewire\Report\Jsh\MaterialAdjustReportJsh;
use App\Http\Livewire\Report\Jsh\ProductJsh;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    // Route::prefix('/')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::prefix('jsh')->name('jsh.')->group(function () {
        Route::get('/material-input', MaterialInput::class)->name('material-input.index');
        Route::get('/material-adjust', JshMaterialAdjust::class)->name('material-adjust.index');
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/total-furnace', AllFurnaceJsh::class)->name('total-furnace-report');
            Route::get('/furnace-report', FurnaceJsh::class)->name('furnace-report');
            Route::get('/product-report', ProductJsh::class)->name('product-report');
            Route::get('/material-adjust-report', MaterialAdjustReportJsh::class)->name('material-adjust-report');
        });
    });

    Route::prefix('ace')->name('ace.')->group(function () {
        Route::get('/material-input', AceMaterialInput::class)->name('material-input-ace.index');
        Route::get('/material-adjust', AceMaterialAdjust::class)->name('material-adjust-ace.index');
        Route::get('/ladle-transfer', MaterialLadleTransfer::class)->name('ladle-transfer.index');
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/total-furnace', AllFurnaceAce::class)->name('total-furnace-report');
            Route::get('/furnace-report', FurnaceAce::class)->name('furnace-report');
            Route::get('/product-report', ProductAce::class)->name('product-report');
            Route::get('/material-adjust-report', MaterialAdjustReportAce::class)->name('material-adjust-report');
        });
    });
    // });
});

require __DIR__ . '/auth.php';
