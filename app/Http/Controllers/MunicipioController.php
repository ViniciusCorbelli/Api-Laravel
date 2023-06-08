<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class MunicipioController extends Controller
{

    const KEY_CACHE_MUNICIPIO = 'municipio_';

    public function getByUf($uf) {

        $response = Redis::get(self::KEY_CACHE_MUNICIPIO . $uf);
        if (!empty($response)) {
            return response()->json(json_decode($response), 200, [], JSON_UNESCAPED_UNICODE);
        }

        $municipios = Http::get('https://brasilapi.com.br/api/ibge/municipios/v1/' . $uf);

        if(!($municipios->ok())) {
            return response()->json([
                'error' => 'Não foi possivel obter os dados dos municipios.'
            ], 400);
        }

        $response = [];
        foreach ($municipios->json() as $municipio) {
            $response[] = [
                'name' => ucfirst(mb_strtolower($municipio['nome'])),
                'ibge_code' => $municipio['codigo_ibge']
            ];
        }

        Redis::set(self::KEY_CACHE_MUNICIPIO . $uf, json_encode($response, JSON_UNESCAPED_UNICODE));

        return response()->json($response , 200, [], JSON_UNESCAPED_UNICODE);
    }
}
