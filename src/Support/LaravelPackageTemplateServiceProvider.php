<?php

namespace Glhd\LaravelPackageTemplate\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LaravelPackageTemplateServiceProvider extends ServiceProvider
{
	public function boot()
	{
		// require_once __DIR__.'/helpers.php';
		// $this->bootConfig();
		// $this->bootViews();
		// $this->bootBladeComponents();
	}
	
	public function register()
	{
		$this->mergeConfigFrom($this->packageConfigFile(), 'laravel-package-template');
	}
	
	protected function bootViews() : self
	{
		$this->loadViewsFrom($this->packageViewsDirectory(), 'laravel-package-template');
		
		$this->publishes([
			$this->packageViewsDirectory() => $this->app->resourcePath('views/vendor/laravel-package-template'),
		], 'laravel-package-template-views');
		
		return $this;
	}
	
	protected function bootBladeComponents() : self
	{
		if (version_compare($this->app->version(), '8.0.0', '>=')) {
			Blade::componentNamespace('Glhd\\LaravelPackageTemplate\\Components', 'laravel-package-template');
		}
		
		return $this;
	}
	
	protected function bootConfig() : self
	{
		$this->publishes([
			$this->packageConfigFile() => $this->app->configPath('laravel-package-template.php'),
		], 'laravel-package-template-config');
		
		return $this;
	}
	
	protected function packageConfigFile(): string
	{
		return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'laravel-package-template.php';
	}
	
	protected function packageTranslationsDirectory(): string
	{
		return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'translations';
	}
	
	protected function packageViewsDirectory(): string
	{
		return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views';
	}
}
