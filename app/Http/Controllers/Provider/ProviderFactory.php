<?php

namespace App\Http\Controllers\Provider;

use Exception;
use ReflectionClass;

class ProviderFactory {

	static function obter($provider) {
		$require = dirname(__FILE__) . '/Providers/' . $provider . '.php';

		if (!file_exists($require)) {
			$error = 'Provider ' . $provider . ' not found.';
			throw new Exception($error);
		}

		$tipo = new ReflectionClass("\\App\\Http\\Controllers\\Provider\\Providers\\".$provider);
		$providerClass = $tipo->newInstance();

		return $providerClass;
	}

}