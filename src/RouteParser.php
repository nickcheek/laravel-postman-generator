<?php

namespace Nickcheek\LaravelPostmanGenerator;

use Illuminate\Routing\Router;

class RouteParser
{
	protected string $basePath = '/api';
	protected array $collection;

	public function __construct(
		protected Router $router,
		protected string $name = 'API Collection'
	) {
		$this->collection = [
			'info' => [
				'name' => $this->name,
				'description' => 'Generated from Laravel routes',
				'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
			],
			'item' => []
		];
	}

	public function generateCollection(): array
	{
		$routes = $this->router->getRoutes();
		$groupedRoutes = [];

		foreach ($routes as $route) {
			if (str_starts_with($route->uri(), 'api/')) {
				$uri = $route->uri();
				$parts = explode('/', trim($uri, '/'));
				$baseGroup = $parts[1] ?? 'default';

				$uri = preg_replace('/\{([^}]+)\}/', ':$1', $uri);

				$name = $route->getName() ?? $uri;

				$routeData = [
					'method' => $route->methods()[0],
					'path' => '/' . $uri,
					'name' => $name
				];

				$groupedRoutes[$baseGroup][] = $routeData;
			}
		}

		foreach ($groupedRoutes as $group => $routes) {
			$folder = [
				'name' => ucfirst($group),
				'item' => []
			];

			usort($routes, fn($a, $b) =>
			strcmp($a['method'], $b['method']) ?: strcmp($a['path'], $b['path'])
			);

			foreach ($routes as $route) {
				$request = [
					'name' => "{$route['method']} {$route['name']}",
					'request' => [
						'method' => $route['method'],
						'url' => [
							'raw' => '{{base_url}}' . $route['path'],
							'host' => ['{{base_url}}'],
							'path' => array_values(array_filter(explode('/', $route['path'])))
						],
						'header' => [
							[
								'key' => 'Accept',
								'value' => 'application/json'
							],
							[
								'key' => 'Content-Type',
								'value' => 'application/json'
							],
							[
								'key' => 'Authorization',
								'value' => 'Bearer {{token}}'
							]
						]
					]
				];

				if (in_array($route['method'], ['POST', 'PUT', 'PATCH'], true)) {
					$request['request']['body'] = [
						'mode' => 'raw',
						'raw' => "{\n    \n}",
						'options' => [
							'raw' => [
								'language' => 'json'
							]
						]
					];
				}

				$folder['item'][] = $request;
			}

			$this->collection['item'][] = $folder;
		}

		return $this->collection;
	}

	public function saveToFile(string $path): bool
	{
		$collection = $this->generateCollection();
		$json = json_encode($collection, JSON_PRETTY_PRINT);

		$directory = dirname($path);
		if (!is_dir($directory)) {
			mkdir($directory, 0755, true);
		}

		return (bool) file_put_contents($path, $json);
	}
}