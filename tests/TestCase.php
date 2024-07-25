<?php

namespace Glhd\Linen\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function fixture(string $filename): string
	{
		return __DIR__.'/fixtures/'.$filename;
	}
	
	protected function getPackageProviders($app)
	{
		return [];
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
