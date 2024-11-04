<?php

namespace Nickcheek\LaravelPostmanGenerator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Nickcheek\LaravelPostmanGenerator\Commands\GeneratePostmanCollection;

class LaravelPostmanGeneratorServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				GeneratePostmanCollection::class,
			]);
		}
	}

	public function register()
	{
		$this->app->bind(RouteParser::class, function ($app) {
			return new RouteParser($app['router']);
		});
	}
}