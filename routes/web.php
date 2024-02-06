<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;

Route::get('/',([GalleryController::class,'index']))
        ->name('main-home');

Route::resource('gallery',(GalleryController::class));
Route::get('gallery/{id}/download',([GalleryController::class,'download']))
        ->name('download');
