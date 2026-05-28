<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\ProductController;
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
     
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);       // Lệnh THÊM
    Route::put('/products/{id}', [ProductController::class, 'update']);  // Lệnh SỬA
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Lệnh XÓA
    Route::post('/receipts', [App\Http\Controllers\Api\ReceiptController::class, 'store']);
    Route::post('/issues', [App\Http\Controllers\Api\IssueController::class, 'store']);
    Route::get('/receipts', [App\Http\Controllers\Api\ReceiptController::class, 'index']); // Dòng lấy lịch sử nhập
    Route::post('/receipts', [App\Http\Controllers\Api\ReceiptController::class, 'store']);
    Route::get('/issues', [App\Http\Controllers\Api\IssueController::class, 'index']);     // Dòng lấy lịch sử xuất
    Route::post('/issues', [App\Http\Controllers\Api\IssueController::class, 'store']);
    });
    Route::get('/products', [ProductController::class, 'index']);