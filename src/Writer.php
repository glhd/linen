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
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\WriterInterface;

abstract class Writer
{
	protected bool $headers = true;
	
	protected Closure $header_formatter;
	
	public static function for(array|Enumerable|Generator|Builder $data): static
	{
		return new static($data);
	}
	
	public function __construct(
		protected array|Enumerable|Generator|Builder $data,
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
	
	public function write(string $path): string
	{
		$writer = $this->writer();
		
		$writer->openToFile($path);
		
		foreach ($this->rows() as $row) {
			$writer->addRow(Row::fromValues($row->toArray()));
		}
		
		$writer->close();
		
		return $path;
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
	
	abstract protected function writer(): WriterInterface;
	
	/** @return Generator<Collection> */
	protected function rows(): Generator
	{
		$source = match (true) {
			$this->data instanceof Generator => LazyCollection::make($this->data),
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
