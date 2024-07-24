<?php

namespace Glhd\Linen;

use Illuminate\Support\LazyCollection;

abstract class Reader
{
	public static function read(string $path): LazyCollection
	{
		return (new static($path))->collect();
	}
	
	public function __construct(
		protected string $path,
	) {
	}
	
	abstract public function collect(): LazyCollection;
}
