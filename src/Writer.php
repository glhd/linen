<?php

namespace Glhd\Linen;

use Closure;
use Generator;
use Glhd\Linen\Support\WriteIterator;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use OpenSpout\Writer\WriterInterface;

abstract class Writer
{
	protected bool $headers = true;
	
	protected Closure $header_formatter;
	
	public static function for(array|Enumerable|Closure|Builder $data): static
	{
		return new static($data);
	}
	
	public function __construct(
		protected array|Enumerable|Closure|Builder $data,
	) {
		$this->header_formatter = Str::headline(...);
	}
	
	public function withoutHeaders(): static
	{
		$this->headers = false;
		
		return $this;
	}
	
	public function withHeaderFormatter(Closure $header_formatter): static
	{
		$this->header_formatter = $header_formatter;
		
		return $this;
	}
	
	public function withOriginalKeysAsHeaders(): static
	{
		return $this->withHeaderFormatter(static fn($key) => $key);
	}
	
	public function getIterator(?string $path = null): WriteIterator
	{
		$path ??= tempnam_with_cleanup();
		
		return new WriteIterator($path, $this->rows(), $this->writer());
	}
	
	public function write(string $path): string
	{
		return $this->getIterator($path)->drain();
	}
	
	public function writeToHttpFile(): File
	{
		return new File($this->writeToTemporaryFile());
	}
	
	public function writeToTemporaryFile(): string
	{
		return $this->write(tempnam_with_cleanup());
	}
	
	abstract protected function writer(): WriterInterface;
	
	/** @return Generator<Collection> */
	protected function rows(): Generator
	{
		$source = match (true) {
			$this->data instanceof Closure => LazyCollection::make($this->data),
			is_array($this->data) => Collection::make($this->data),
			$this->data instanceof Builder => $this->data->lazyById(),
			default => $this->data,
		};
		
		$needs_headers = $this->headers;
		
		foreach ($source as $row) {
			$row = Collection::make($row);
			
			if ($needs_headers) {
				$needs_headers = false;
				yield $row->keys()->map($this->header_formatter);
			}
			
			yield $row;
		}
	}
}
