<?php

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $word = Word::inRandomOrder()->first();
    return view('trainer', compact('word'));
});

Route::get('/get-word', function () {
    return response()->json(Word::inRandomOrder()->first());
});
