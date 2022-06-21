<?php

use Illuminate\Support\Facades\Route;

Route::get('/',['as'=>'index','uses'=>'App\Http\Controllers\CotacaoController@index']);