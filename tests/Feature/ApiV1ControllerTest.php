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

        $response->assertStatus(200);
    }

    public function test_with_invalid_municipio()
    {
        $response = $this->get('/api/v1/municipios/AA');

        $response->assertStatus(404)
                ->assertJson(['error' => ApiV1Controller::NOT_FOUND]);
    }

}
