<?php

namespace Glhd\LaravelPackageTemplate\Tests;

use Glhd\LaravelPackageTemplate\Support\LaravelPackageTemplateServiceProvider;
use Illuminate\Container\Container;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function getPackageProviders($app)
	{
		return [
			LaravelPackageTemplateServiceProvider::class,
		];
	}
	
	protected function getPackageAliases($app)
	{
		return [];
	}
	
	protected function getApplicationTimezone($app)
	{
		return 'America/New_York';
	}
}
