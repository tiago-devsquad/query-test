<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/topic/{topic}', function () {
    return 'topic';
})->name('topic');

Route::get('/chapter/{chapter}', function () {
    return 'chapter';
})->name('chapter');

Route::get('/newspaper/{newspaper}', function () {
    return 'newspaper';
})->name('newspaper');

Route::get('/case-study/{caseStudy}', function () {
    return 'case-study';
})->name('case-study');