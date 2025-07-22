<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Post\Index as PostIndex;
use App\Livewire\User\Index as UserIndex;

Route::get('/', PostIndex::class);
Route::get('/users', UserIndex::class)->name('users.index');
