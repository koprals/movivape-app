<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\AnalysisController;
use App\Admin\Controllers\DashboardController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'DashboardController@index')->name('home');
    $router->resource('/products', ProductsController::class);
    $router->resource('/orders', OrdersController::class);
    $router->resource('/order-details', OrderDetailsController::class);

    // Routes For Analysis page
    Route::get('analysis', [AnalysisController::class, 'index'])->name('analysis');
    Route::post('analysis/step1', [AnalysisController::class, 'step1'])->name('analysis.step1');
    Route::get('analysis/step2', [AnalysisController::class, 'step2'])->name('analysis.step2');
    Route::get('analysis/step3', [AnalysisController::class, 'step3'])->name('analysis.step3');
    Route::get('analysis/step4', [AnalysisController::class, 'step4'])->name('analysis.step4');
    Route::get('analysis/step5', [AnalysisController::class, 'step5'])->name('analysis.step5');

    // Routes for dashboard page
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
