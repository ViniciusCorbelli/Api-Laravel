<?php

namespace App\Http\Controllers\Provider\Interfaces;

interface IApiMunicipio {
	public function get(string $uf);
}