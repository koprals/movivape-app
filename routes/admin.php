<?php
use App\Admin\Controllers\AnalysisController;

Admin::routes();

Route::get('analysis', [AnalysisController::class, 'index'])->name('admin.analysis.page');
Route::post('analysis/generate', [AnalysisController::class, 'generateAnalysis'])->name('admin.analysis.generate');
Route::get('analysis/result', [AnalysisController::class, 'result'])->name('admin.analysis.result');
