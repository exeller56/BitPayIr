<?php

use Illuminate\Support\Facades\Route;

Route::post(uri : '/bitpay/webhook', action:  [App\Extensions\Gateways\BitPayIr\BitPayIr::class,'webhook'])->name('bitpay.webhook');
