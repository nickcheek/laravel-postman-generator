<?php

namespace Nickcheek\LaravelPostmanGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Nickcheek\LaravelPostmanGenerator\RouteParser;

class GeneratePostmanCollection extends Command
{
	protected $signature = 'postman:generate 
        {output? : The output path for the collection}
        {--N|name=Laravel API : The name of the collection}';

	protected $description = 'Generate a Postman collection from Laravel route api file';

	protected $router;

	public function __construct(Router $router)
	{
		parent::__construct();
		$this->router = $router;
	}

	public function handle()
	{
		$outputPath = $this->argument('output');
		if (empty($outputPath)) {
			$outputPath = base_path('postman-collection.json');
		}

		$collectionName = $this->option('name');

		try {
			$parser = new RouteParser($this->router, $collectionName);
			$parser->saveToFile($outputPath);

			$this->info('Postman collection generated successfully!');
			$this->line('Collection Name: ' . $collectionName);
			$this->line('Output saved to: ' . $outputPath);

			return 0;
		} catch (\Exception $e) {
			$this->error('Failed to generate Postman collection: ' . $e->getMessage());
			return 1;
		}
	}
}