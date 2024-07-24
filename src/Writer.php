<?php

namespace Glhd\Linen;

use Closure;
use Generator;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;

abstract class Writer
{
	protected Closure $header_formatter;
	
	abstract public function write(string $path): string;
	
	public static function for(array|Enumerable|Generator|Builder $data): static
	{
		return new static($data);
	}
	
	public function __construct(
		protected array|Enumerable|Generator|Builder $data,
		protected bool $headers = true,
	) {
		$this->header_formatter = Str::headline(...);
	}
	
	public function withoutHeaders(): static
	{
		$this->headers = false;
		
		return $this;
	}
	
	public function withOriginalKeysAsHeaders(): static
	{
		$this->header_formatter = static fn($key) => $key;
		
		return $this;
	}
	
	public function writeToHttpFile(): File
	{
		$path = $this->writeToTemporaryFile();
		
		return new File($path);
	}
	
	public function writeToTemporaryFile(): string
	{
		$path = tempnam(sys_get_temp_dir(), 'glhd-linen-data');
		
		App::terminating(fn() => @unlink($path));
		
		return $this->write($path);
	}
	
	protected function rows(): Generator
	{
		$source = match (true) {
			$this->data instanceof Generator => LazyCollection::make($this->data),
			is_array($this->data) => Collection::make($this->data),
			$this->data instanceof Builder => $this->data->lazyById(),
			default => $this->data,
		};
		
		foreach ($source as $row) {
			yield Collection::make($row);
		}
	}
}
