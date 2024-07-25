<?php

namespace Glhd\Linen\Support;

use Illuminate\Support\ServiceProvider;

class LinenServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// require_once __DIR__.'/helpers.php';
		
		$this->bootConfig();
	}
	
	public function register()
	{
		$this->mergeConfigFrom($this->packageConfigFile(), 'linen');
	}
	
	protected function bootConfig(): self
	{
		$this->publishes([
			$this->packageConfigFile() => $this->app->configPath('linen.php'),
		], ['linen', 'linen-config']);
		
		return $this;
	}
	
	protected function packageConfigFile(): string
	{
		return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'linen.php';
	}
}
