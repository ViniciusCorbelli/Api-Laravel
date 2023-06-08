<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class ApiV1Controller extends Controller
{

    const KEY_CACHE_MUNICIPIO = 'municipio_';

    public function getByUf(Request $request) {
        $uf = $request->route('uf');

        $response = Redis::get(self::KEY_CACHE_MUNICIPIO . $uf);
        if (!empty($response)) {
            return response()->json(json_decode($response), 200, [], JSON_UNESCAPED_UNICODE);
        }

        $municipios = Http::get(env('URL_API_BRASIL') . $uf);

        if(!($municipios->ok())) {
            return response()->json([
                'error' => 'It was not possible to obtain the data of the municipalities.'
            ], 404);
        }

        $response = [];
        foreach ($municipios->json() as $municipio) {
            $response[] = [
                'name' => ucfirst(mb_strtolower($municipio['nome'])),
                'ibge_code' => $municipio['codigo_ibge']
            ];
        }

        Redis::set(self::KEY_CACHE_MUNICIPIO . $uf, json_encode($response, JSON_UNESCAPED_UNICODE));

        $paginated = CollectionHelper::paginate(collect($response), 20);
        return response()->json($paginated , 200, [], JSON_UNESCAPED_UNICODE);
    }
}
