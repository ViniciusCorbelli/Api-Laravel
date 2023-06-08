<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Provider\ProviderFactory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ApiV1Controller extends Controller
{

    const KEY_CACHE_MUNICIPIO = 'municipio_';
    const NOT_FOUND = 'It was not possible to obtain the data of the municipalities.';

    public function getByUf(Request $request) {
       try {
            $response = $this->get($request->route('uf'));

            $paginated = CollectionHelper::paginate(collect($response), $request->query('perPage') ?? 20);
            return response()->json($paginated , 200, [], JSON_UNESCAPED_UNICODE);

       } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
       }
    }

    private function get(string $uf) {
        try {
            /*$response = Redis::get(self::KEY_CACHE_MUNICIPIO . $uf);
            if (!empty($response)) {
                return json_decode($response);
            }*/

            $provider = ProviderFactory::obter(env('PROVIDER'));
            $response = $provider->get($uf);

            Redis::set(self::KEY_CACHE_MUNICIPIO . $uf, json_encode($response, JSON_UNESCAPED_UNICODE));

            return $response;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
       }
    }
}
