<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class ApiV1Controller extends Controller
{

    const KEY_CACHE_MUNICIPIO = 'municipio_';
    const NOT_FOUND = 'It was not possible to obtain the data of the municipalities.';

    public function getByUf(Request $request) {
       try {
            $response = $this->getByBrasilApi($request->route('uf'));

            $paginated = CollectionHelper::paginate(collect($response), $request->query('perPage') ?? 20);
            return response()->json($paginated , 200, [], JSON_UNESCAPED_UNICODE);

       } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
       }
    }

    private function getByBrasilApi(string $uf) {
        $response = Redis::get(self::KEY_CACHE_MUNICIPIO . $uf);
        if (!empty($response)) {
            return json_decode($response);
        }

        $municipios = Http::get(env('URL_API_BRASIL') . $uf);

        if(!($municipios->ok())) {
            throw new Exception(self::NOT_FOUND);
        }

        $response = [];
        foreach ($municipios->json() as $municipio) {
            $response[] = [
                'name' => ucfirst(mb_strtolower($municipio['nome'])),
                'ibge_code' => $municipio['codigo_ibge']
            ];
        }

        Redis::set(self::KEY_CACHE_MUNICIPIO . $uf, json_encode($response, JSON_UNESCAPED_UNICODE));
        return $response;
    }
}
