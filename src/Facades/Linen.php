<?php

namespace Glhd\Linen\Facades;

use Glhd\Linen\Support\FileTypeHelper;
use Illuminate\Support\Facades\Facade;

class Linen extends Facade
{
	protected static function getFacadeAccessor()
	{
		return FileTypeHelper::class;
	}
}
