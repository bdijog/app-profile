<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/set-locale/{lang}', function (string $lang) {
    if (in_array($lang, ['id', 'en'], true)) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }

    return redirect()->back();
})->name('set-locale');
