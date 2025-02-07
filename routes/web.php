<?php

use Illuminate\Support\Facades\Route;
use App\Models\Word;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('trainer', ['words' => Word::all()]);
});

Route::post('/check', function (Request $request) {
    $word = Word::find($request->id);
    $correct = strtolower($request->answer) === strtolower($word->english);
    return response()->json(['correct' => $correct]);
});
