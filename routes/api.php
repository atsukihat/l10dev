<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LectureDetailsController;
use App\Http\Controllers\LecturesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('login',[UserController::class,'login']);
// Route::post('/register', [ApiController::class, 'register']);
Route::post('/register', [UserController::class, 'create']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::post('/reviews', [ReviewsController::class, 'store']);
// 問い合わせ
Route::post('/contact', [ContactController::class, 'store']);

// プロフィール
Route::get('/profile', [UserController::class, 'show']);

// プロフィール情報の取得
Route::get('/initialValues', [UserController::class, 'initialValues']);

// プロフィール編集(プロフィール更新)
Route::post('/update', [UserController::class, 'update']);

// 講義コードを持つかどうかの検索画面
Route::post('/hasLectureCode', [LectureDetailsController::class, 'hasLectureCode']);

// 講義Idに対する講義情報を返却する
Route::post('/searchByLectureId', [LectureDetailsController::class, 'searchByLectureId']);


// 講義コードの有無を検索
Route::post('/hasLectureCode', [LectureDetailsController::class, 'hasLectureCode']);

// 講義コードから詳細情報を検索
Route::post('/search', [LectureDetailsController::class, 'search']);

// 授業一覧を返却する
Route::get('/getLectureInfo', [LecturesController::class, 'getLectureInfo']);

// 検索画面の「条件から探す」から詳細情報を検索
Route::get('/searchByConditions', [LecturesController::class, 'searchByConditions']);

// レビュー削除
Route::delete('/deleteReview/{id}',[ReviewsController::class, 'destroy']);

// 総レビュー数の取得
Route::get('/allReviewCount',[ReviewsController::class, 'allReviewCount']);

// パスワード再設定用のリンクをEmailに送信
Route::post('/password/reset-request', [UserController::class, 'sendResetLink'])->name('password.reset-request');

// Emailに送信されたリンクからパスワードを再設定
Route::post('/password/reset', [UserController::class, 'resetPasswordFromLink'])->name('password.reset');