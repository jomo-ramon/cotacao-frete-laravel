<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transportadora; 

class TransportadoraController extends Controller
{
    public function create(Request $Request)
    {
        try {
            $Transportadora = new Transportadora();
            $Transportadora->name = $Request->name;
            $Transportadora->save();
    
            return response()->json([
                "message" => "Transportadora record created"
            ], 200);
        } catch (Exception $e) {

        }
      
    }

    public function edit($id)
    {
        //
    }

    public function getById(int $transportadoraID) {
        try {
            $Transportadora = new Transportadora();
            $Transportadora = $Transportadora->find($transportadoraID);

            if (isset($Transportadora->id)) { return true; } else { return false;}
        } catch (Excepetion $e) {

        }
    }

    public function update(Request $Request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}