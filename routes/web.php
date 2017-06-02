<?php

Route::get('/', function () {
    return redirect('/admin/quotation');
});

// Auth routes
Route::auth();
// Route::get('register', function(){abort(404);})->name('register');
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::get('logout', 'Auth\LoginController@logout');
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

// Admin
Route::group(['prefix' => 'admin'], function () {
    // Account
    Route::get('account', 'AccountController@index');
    Route::post('account/email', 'AccountController@updateEmail');
    Route::post('account/password', 'AccountController@updatePassword');
    // quotation
    Route::resource('quotation', 'QuotationController', ['except' => 'show']);
    Route::get('quotation/preview', 'QuotationController@preview');
    Route::get('quotation/{id}/pdf', 'QuotationController@pdf');
    Route::get('quotation/{id}/print', 'QuotationController@printPDF');
    Route::resource('invoice', 'InvoiceController', ['except' => 'show']);
    Route::get('invoice/preview', 'InvoiceController@preview');
    Route::get('invoice/{id}/pdf', 'InvoiceController@pdf');
    Route::get('invoice/{id}/print', 'InvoiceController@printPDF');
});
