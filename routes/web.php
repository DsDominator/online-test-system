<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\ExamController as StudentExam;
use App\Http\Controllers\Admin\ExamController as AdminExam;
use App\Http\Controllers\Admin\QuestionController as AdminQuestion;
use App\Http\Controllers\Admin\ResultController as AdminResult;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::middleware(['auth'])->group(function () {
    // Student
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('exams', [StudentExam::class,'list'])->name('exams.list');
        Route::get('exams/{exam}', [StudentExam::class,'start'])->name('exams.start');
        Route::post('exams/{exam}/submit', [StudentExam::class,'submit'])->name('exams.submit');
        Route::get('results/{exam}', [StudentExam::class,'showResult'])->name('results.show');
    });

    // Admin
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Exams CRUD (resource duy nhất)
        Route::resource('exams', AdminExam::class);

        // Questions (nested dưới exam)
        Route::resource('exams.questions', AdminQuestion::class)
             ->shallow(); // tạo routes ngắn cho show/edit/update/destroy

        // Results
        Route::get('results', [AdminResult::class,'index'])->name('results.index');
        Route::get('results/{exam}/{user}', [AdminResult::class,'show'])->name('results.show');
        Route::post('results/{exam}/answers/{answer}/score', [AdminResult::class,'scoreAnswer'])->name('results.score');
        Route::post('results/{exam}/{user}/finalize', [AdminResult::class,'finalizeAndNotify'])->name('results.finalize');
    });
});

Route::get('/', fn () => redirect()->route('student.exams.list'));
require __DIR__.'/auth.php';


