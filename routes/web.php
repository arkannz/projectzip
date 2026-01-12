<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\RabController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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
    Route::get('/admin/activities', [DashboardController::class, 'activities'])->name('admin.activities');
});

Route::middleware('auth')->group(function () {
    // Inventory routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/print', [InventoryController::class, 'print'])->name('inventory.print');
    Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');

    // Angkutan routes
    Route::get('/inventory/angkutan', [InventoryController::class, 'angkutan'])->name('inventory.angkutan');
    Route::get('/inventory/angkutan/print', [InventoryController::class, 'printAngkutan'])->name('inventory.angkutan.print');
    Route::post('/inventory/angkutan', [InventoryController::class, 'storeAngkutan'])->name('inventory.angkutan.store');
    Route::put('/inventory/angkutan/{id}', [InventoryController::class, 'updateAngkutan'])->name('inventory.angkutan.update');
    Route::delete('/inventory/angkutan/{id}', [InventoryController::class, 'destroyAngkutan'])->name('inventory.angkutan.destroy');

    // Master item routes
    Route::post('/inventory/item', [InventoryController::class, 'storeItem'])->name('inventory.item.store');
    Route::put('/inventory/item/{item}', [InventoryController::class, 'updateItem'])->name('inventory.item.update');
    Route::delete('/inventory/item/{item}', [InventoryController::class, 'destroyItem'])->name('inventory.item.destroy');

    // Master data routes
    Route::post('/inventory/add-location', [InventoryController::class, 'addLocation'])->name('inventory.add.location');
    Route::post('/inventory/add-type', [InventoryController::class, 'addType'])->name('inventory.add.type');
    Route::post('/inventory/add-unit', [InventoryController::class, 'addUnit'])->name('inventory.addUnit');

    // Transaksi routes
    Route::post('/inventory/masuk', [InventoryController::class, 'storeIn'])->name('inventory.in.store');
    Route::post('/inventory/keluar', [InventoryController::class, 'storeOut'])->name('inventory.out.store');
});

// RAB Routes
Route::middleware('auth')->group(function () {
    Route::get('/rab', [RabController::class, 'index'])->name('rab.index');
    Route::post('/rab/store', [RabController::class, 'store'])->name('rab.store');

    // RAB Type 50 Routes
    Route::get('/rab/type50', [RabController::class, 'type50'])->name('rab.type50');
    Route::get('/rab/type50/print', [RabController::class, 'type50Print'])->name('rab.type50.print');
    
    // RAB Type 55 Routes
    Route::get('/rab/type55', [RabController::class, 'type55'])->name('rab.type55');
    Route::get('/rab/type55/print', [RabController::class, 'type55Print'])->name('rab.type55.print');
    
    // RAB Type 40 Routes
    Route::get('/rab/type40', [RabController::class, 'type40'])->name('rab.type40');
    Route::get('/rab/type40/print', [RabController::class, 'type40Print'])->name('rab.type40.print');
    
    // RAB Type 45 Routes
    Route::get('/rab/type45', [RabController::class, 'type45'])->name('rab.type45');
    Route::get('/rab/type45/print', [RabController::class, 'type45Print'])->name('rab.type45.print');
    
    // RAB Type 60 Routes
    Route::get('/rab/type60', [RabController::class, 'type60'])->name('rab.type60');
    Route::get('/rab/type60/print', [RabController::class, 'type60Print'])->name('rab.type60.print');
    
    // RAB Type 70 Routes
    Route::get('/rab/type70', [RabController::class, 'type70'])->name('rab.type70');
    Route::get('/rab/type70/print', [RabController::class, 'type70Print'])->name('rab.type70.print');
    
    // RAB Type 80 Routes
    Route::get('/rab/type80', [RabController::class, 'type80'])->name('rab.type80');
    Route::get('/rab/type80/print', [RabController::class, 'type80Print'])->name('rab.type80.print');
    
    // RAB Type 100 Routes
    Route::get('/rab/type100', [RabController::class, 'type100'])->name('rab.type100');
    Route::get('/rab/type100/print', [RabController::class, 'type100Print'])->name('rab.type100.print');
    
    // RAB Type 107 Routes
    Route::get('/rab/type107', [RabController::class, 'type107'])->name('rab.type107');
    Route::get('/rab/type107/print', [RabController::class, 'type107Print'])->name('rab.type107.print');
    
    // Shared RAB Routes
    Route::put('/rab/item/{item}', [RabController::class, 'updateItem'])->name('rab.update-item');
    Route::post('/rab/batch-update', [RabController::class, 'batchUpdate'])->name('rab.batch-update');
    Route::post('/rab/update-borongan', [RabController::class, 'updateCategoryBorongan'])->name('rab.update-borongan');
    Route::get('/rab/summary', [RabController::class, 'getSummary'])->name('rab.summary');
    Route::post('/rab/refresh-prices', [RabController::class, 'refreshPrices'])->name('rab.refresh-prices');
    Route::post('/rab/regenerate', [RabController::class, 'regenerate'])->name('rab.regenerate');
});


require __DIR__.'/auth.php';