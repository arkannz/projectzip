<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\RabController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // master item
    Route::post('/inventory/item', [InventoryController::class, 'storeItem'])->name('inventory.item.store');
    Route::put('/inventory/item/{item}', [InventoryController::class, 'updateItem'])->name('inventory.item.update');
    Route::delete('/inventory/item/{item}', [InventoryController::class, 'destroyItem'])->name('inventory.item.destroy');

    // transaksi masuk
    Route::post('/inventory/masuk', [InventoryController::class, 'storeIn'])->name('inventory.in.store');

    // transaksi keluar
    Route::post('/inventory/keluar', [InventoryController::class, 'storeOut'])->name('inventory.out.store');
});

Route::put('/inventory/item/{item}/update', [InventoryController::class, 'updateItem'])
    ->name('inventory.item.update');

Route::post('/inventory/add-location', [App\Http\Controllers\Admin\InventoryController::class, 'addLocation'])
    ->name('inventory.add.location');

Route::post('/inventory/add-type', [App\Http\Controllers\Admin\InventoryController::class, 'addType'])
    ->name('inventory.add.type');

    // Tambah unit rumah
Route::post('/inventory/add-unit', [App\Http\Controllers\Admin\InventoryController::class, 'addUnit'])
    ->name('inventory.addUnit');

Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');

// RAB Routes
Route::middleware('auth')->group(function () {
    Route::get('/rab', [RabController::class, 'index'])->name('rab.index');
    Route::post('/rab/store', [RabController::class, 'store'])->name('rab.store');

    // RAB Type 50 Routes
    Route::get('/rab/type50', [RabController::class, 'type50'])->name('rab.type50');
    Route::get('/rab/type50/print', [RabController::class, 'type50Print'])->name('rab.type50.print');
    Route::put('/rab/item/{item}', [RabController::class, 'updateItem'])->name('rab.update-item');
    Route::post('/rab/batch-update', [RabController::class, 'batchUpdate'])->name('rab.batch-update');
    Route::post('/rab/update-borongan', [RabController::class, 'updateCategoryBorongan'])->name('rab.update-borongan');
    Route::get('/rab/summary', [RabController::class, 'getSummary'])->name('rab.summary');
    Route::post('/rab/refresh-prices', [RabController::class, 'refreshPrices'])->name('rab.refresh-prices');
    Route::post('/rab/sync-bahan-baku', [RabController::class, 'syncBahanBaku'])->name('rab.sync-bahan-baku');
    Route::post('/rab/regenerate', [RabController::class, 'regenerate'])->name('rab.regenerate');
});


require __DIR__.'/auth.php';