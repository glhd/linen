<?php

namespace Glhd\Linen\Tests;

use Glhd\Linen\Support\LinenServiceProvider;
use Illuminate\Container\Container;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function fixture(string $filename): string
	{
		return __DIR__.'/fixtures/'.$filename;
	}
	
	protected function getPackageProviders($app)
	{
		return [
			LinenServiceProvider::class,
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
