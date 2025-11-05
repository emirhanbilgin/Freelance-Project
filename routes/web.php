<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReceiptItemController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [ReceiptController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Yetkili kullanıcılar
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fiş işlemleri
    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('/receipts/archived', [ReceiptController::class, 'archived'])->name('receipts.archived');
    Route::get('/receipts/archived/{date}', [ReceiptController::class, 'archivedByDate'])->name('receipts.archived.by-date');
    Route::get('/receipts/credit', [ReceiptController::class, 'creditReceipts'])->name('receipts.credit');
    Route::get('/receipts/create', [ReceiptController::class, 'create'])->name('receipts.create');
    Route::post('/receipts', [ReceiptController::class, 'store'])->name('receipts.store');
    Route::get('/receipts/{id}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::put('/receipts/{id}/payment-method', [ReceiptController::class, 'updatePaymentMethod'])->name('receipts.update-payment-method');
    Route::get('/receipts/{id}/edit', [ReceiptController::class, 'edit'])->name('receipts.edit');

    Route::post('/receipts/bulk-delete', [ReceiptController::class, 'bulkDelete'])->name('receipts.bulk-delete');

    // ✅ PDF oluşturma
    Route::get('/receipts/{id}/pdf', [ReceiptController::class, 'generatePDF'])->name('receipts.pdf');

    // Ürün işlemleri (fişe ürün ekleme ve güncelleme)
    Route::post('/receipts/{receipt}/items', [ReceiptItemController::class, 'store'])->name('receipt_items.store');
    Route::put('/receipt-items/{id}', [ReceiptItemController::class, 'update'])->name('receipt_items.update');
    // Bu SATIR dışında update_batch için başka hiçbir şey olmasın!
    // Bu satır ReceiptController ile olmalı. Sadece BİR tane olsun.
    Route::put('/receipts/{receipt}/items/update-batch', [ReceiptController::class, 'updateBatch'])->name('receipt_items.update_batch');

    // Müşteri işlemleri
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/customers/{id}/receipts', [CustomerController::class, 'receipts'])->name('customers.receipts');

    // API Endpoints
    Route::get('/api/daily-sales-report', [ReceiptController::class, 'dailySalesReport'])->name('api.daily-sales-report');
});

require __DIR__.'/auth.php';
