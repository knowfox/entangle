<?php

Route::group(['middleware' => 'web'], function () {
    Route::get('/timeline', [
        'as' => 'entangle.timeline',
        'uses' => EntangledController::class . '@timelines',
    ]);

    Route::get('/entangle', [
        'as' => 'entangle.import',
        'uses' => ImportController::class . '@index',
    ]);

    Route::post('/entangle', [
        'as' => 'entangle.save',
        'uses' => ImportController::class . '@save',
    ]);

    Route::get('/person/{slug}', [
        'as' => 'entangle.person',
        'uses' => PersonController::class . '@show',
    ]);
});

