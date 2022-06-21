<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotacaoFrete extends Model
{
    protected $fillable = [
        'uf',
        'percentual_cotacao',
        'extra_value',
        'transportadora_id',
    ];
}