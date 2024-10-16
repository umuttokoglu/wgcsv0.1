<?php

use App\Constants\MockProviderConstants;
use Illuminate\Support\Facades\Route;

Route::get('/provider/1', function () {
    return response()->json(MockProviderConstants::PROVIDER_1_RESULT);
});

Route::get('/provider/2', function () {
    return response()->json(MockProviderConstants::PROVIDER_2_RESULT);
});
