<?php
use App\Http\Controllers\TransportadoraController;
use App\Http\Controllers\CotacaoController;

Route::post('transportadora', [TransportadoraController::class, 'create']); 
Route::post('cotacao', [CotacaoController::class, 'create']); 
Route::put('cotacao', [CotacaoController::class, 'calculeSimulate']); 
Route::get('cotacao', [CotacaoController::class, 'get']); 