<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CotacaoFrete; 
use App\Models\Transportadora; 
use App\Models\Estados; 
use App\Http\Controllers\TransportadoraController; 


class CotacaoController extends Controller
{
    public function index()
    {
        $cotacao = CotacaoFrete::all();
        $transportadoras = Transportadora::all();
        $ufs = Estados::all();
        return view('index', compact('cotacao', 'ufs', 'transportadoras'));
    }

    public function create(Request $request)
    {
        try {
            $request = json_encode($request->all());   
            $request = json_decode($request);  
            
            if (!isset($request->uf)) return response()->json(["message" => "UF é obrigatorio"], 500);

            if (!isset($request->percentual_cotacao)) return response()->json(["message" => "Percentual é obrigatorio"], 500);

            if (!isset($request->valor_extra)) return response()->json(["message" => "Valor extra é obrigatorio"], 500);

            if (!isset($request->transportadora_id)) return response()->json(["message" => "ID Transportadora é obrigatorio"], 500);

            // Validação extra, verificando se a transportadora existe!
            $Transportadora = new TransportadoraController; 
            $Transportadora = $Transportadora->getById($request->transportadora_id); 
            if (!$Transportadora) return response()->json(["message" => "Transportadora não existe"], 500);
        
            // Validação transportadora e uf.
            if ($this->validateUfAndTransportadora($request)) return response()->json(["message" => "uf já cadastrado para essa transportadora"], 500);

            $CotacaoFrete = new CotacaoFrete();
            $CotacaoFrete->uf = $request->uf;
            $CotacaoFrete->percentual_cotacao = $request->percentual_cotacao;
            $CotacaoFrete->extra_value = $request->valor_extra;
            $CotacaoFrete->transportadora_id = $request->transportadora_id;
            $Save = $CotacaoFrete->save();

            if ($Save) {
                return response()->json([
                    "message" => "Cotação cadastrada com sucesso",
                    "status" => 200
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                "message" => "Tente novamente mais tarde", 
                "status" => 500
            ], 500);
        }
   
    }

    protected function validateUfAndTransportadora($options) 
    {
        try {
      
            $Cotacao = CotacaoFrete::where('uf', '=', $options->uf)->where('transportadora_id', '=', $options->transportadora_id)->get();

            if (count($Cotacao) > 0) {
                return true; 
            } else {
                return false;
            }
        } catch (Excepetion $e) {

        }
    }

    public function calculeSimulate(Request $request) 
    {
        try {    
            $request = json_encode($request->all());   
            $request = json_decode($request);  
            
            if (!isset($request->uf)) return response()->json(["message" => "UF é obrigatorio"], 500);

            if (!isset($request->valor_pedido)) return response()->json(["message" => "valor do pedido é obrigatorio"], 500);
    
            $cotacao = CotacaoFrete::where('uf', '=', $request->uf)->get();
     
            $cotacoes = array();
            
            if (count($cotacao) > 0) {
                foreach ($cotacao as $key => $value) {
                   $valor_cotacao = number_format(($request->valor_pedido/100 * $value['percentual_cotacao']) + $value['extra_value'], 2);
                   $cotacoes[$valor_cotacao]['valor_cotacao'] = $valor_cotacao;
                   $cotacoes[$valor_cotacao]['transportadora_id'] = $value->transportadora_id;
                }
            } else {
                return response()->json(["message" => "Cotação não cadastrada o UF."], 500);
            }

            krsort($cotacoes);
            
            if (isset($cotacoes)) {
                return response()->json($cotacoes, 200);
            }
            
          
        } catch(Exception $e) {

        }
       

    }

}