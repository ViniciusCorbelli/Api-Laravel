<?php

namespace Tests\Feature;

use App\Http\Controllers\ApiV1Controller;
use Tests\TestCase;

class ApiV1ControllerTest extends TestCase
{
    public function test_with_municipio_correct_complet()
    {
        $response = $this->get('/api/v1/municipios/rs?page=1&perPage=50');

        $response->assertStatus(200);
    }

    public function test_with_municipio_correct_complet_cache()
    {
        $response = $this->get('/api/v1/municipios/rs?page=1&perPage=50');
        $response_cache = $this->get('/api/v1/municipios/rs?page=1&perPage=50');

        $response->assertStatus(200);
        $response_cache->assertStatus(200);
        $response->assertJson($response_cache->json());
    }

    public function test_with_municipio_correct_only_query_page()
    {
        $response = $this->get('/api/v1/municipios/mg?page=1');

        $response->assertStatus(200);
    }

    public function test_with_municipio_correct_only_query_perPage()
    {
        $response = $this->get('/api/v1/municipios/mg?perPage=1');

        $response->assertStatus(200)
                ->assertSimilarJson(json_decode('{"current_page":1,"data":[{"ibge_code":"3100104","name":"Abadia dos dourados"}],"first_page_url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=1","from":1,"last_page":853,"last_page_url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=853","links":[{"active":false,"label":"&laquo; Previous","url":null},{"active":false,"label":"...","url":null},{"active":false,"label":"2","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=2"},{"active":false,"label":"3","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=3"},{"active":false,"label":"4","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=4"},{"active":false,"label":"5","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=5"},{"active":false,"label":"6","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=6"},{"active":false,"label":"7","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=7"},{"active":false,"label":"8","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=8"},{"active":false,"label":"9","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=9"},{"active":false,"label":"10","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=10"},{"active":false,"label":"852","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=852"},{"active":false,"label":"853","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=853"},{"active":false,"label":"Next &raquo;","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=2"},{"active":true,"label":"1","url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=1"}],"next_page_url":"http:\/\/localhost\/api\/v1\/municipios\/mg?page=2","path":"http:\/\/localhost\/api\/v1\/municipios\/mg","per_page":"1","prev_page_url":null,"to":1,"total":853}', true));
    }

    public function test_with_invalid_municipio()
    {
        $response = $this->get('/api/v1/municipios/AA');

        $response->assertStatus(404)
                ->assertJson(['error' => ApiV1Controller::NOT_FOUND]);
    }

}
