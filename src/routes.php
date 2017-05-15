<?php

use Knowfox\Entangle\Controllers\EntangledController;

Route::group(['middleware' => 'web'], function () {
    Route::get('/timeline', [
        'as' => 'entangle.timeline',
        'uses' => EntangledController::class . '@timelines'
    ]);
});

