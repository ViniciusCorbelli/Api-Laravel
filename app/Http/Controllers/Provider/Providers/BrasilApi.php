<?php

namespace App\Http\Controllers\Provider\Providers;

use App\Http\Controllers\ApiV1Controller as ControllersApiV1Controller;
use App\Http\Controllers\Provider\Interfaces\IApiMunicipio;
use Exception;
use Illuminate\Support\Facades\Http;

class BrasilApi implements IApiMunicipio {

    public function get(string $uf) {
        $municipios = Http::get('https://brasilapi.com.br/api/ibge/municipios/v1/' . $uf);

        if (!$municipios->ok() || count($municipios->json()) == 0) {
            throw new Exception(ControllersApiV1Controller::NOT_FOUND);
        }

        $response = [];
        foreach ($municipios->json() as $municipio) {
            $response[] = [
                'name' => ucfirst(mb_strtolower($municipio['nome'])),
                'ibge_code' => (int) $municipio['codigo_ibge']
            ];
        }

        return $response;
    }
    
}
					