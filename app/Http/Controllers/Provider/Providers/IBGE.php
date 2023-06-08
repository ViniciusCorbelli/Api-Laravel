<?php

namespace App\Http\Controllers\Provider\Providers;

use App\Http\Controllers\ApiV1Controller as ControllersApiV1Controller;
use App\Http\Controllers\Provider\Interfaces\IApiMunicipio;
use Exception;
use Illuminate\Support\Facades\Http;

class IBGE implements IApiMunicipio {

    public function get(string $uf) {
        $municipios = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $uf . '/municipios');

        if (!$municipios->ok() || count($municipios->json()) == 0) {
            throw new Exception(ControllersApiV1Controller::NOT_FOUND);
        }

        $response = [];
        foreach ($municipios->json() as $municipio) {
            $response[] = [
                'name' => ucfirst(mb_strtolower($municipio['nome'])),
                'ibge_code' => (int) $municipio['id']
            ];
        }

        return $response;
    }
    
}
					