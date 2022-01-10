<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('mollie/payment')->group(function () {

        Route::get('/redirect', 'Webkul\Mollie\Http\Controllers\MollieController@redirect')->name('mollie.payment.redirect');

        Route::get('/success', 'Webkul\Mollie\Http\Controllers\MollieController@success')->name('mollie.payment.success');
    });
});

Route::get('mollie/payment/webhook', 'Webkul\Mollie\Http\Controllers\MollieController@webhook')->name('mollie.payment.webhook.get');

Route::post('mollie/payment/webhook', 'Webkul\Mollie\Http\Controllers\MollieController@webhook')->name('mollie.payment.webhook');
