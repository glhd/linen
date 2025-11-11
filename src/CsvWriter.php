<?php

namespace Glhd\Linen;

use Closure;
use Glhd\Linen\Support\WriteIterator;
use OpenSpout\Writer\CSV as OpenSpout;
use OpenSpout\Writer\WriterInterface;

class CsvWriter extends Writer
{
	protected string $delimiter = ',';
	
	protected string $enclosure = '"';
	
	protected bool $bom = true;
	
	protected bool $empty_new_line = false;
	
	public function withDelimiter(string $delimiter): static
	{
		$this->delimiter = $delimiter;
		
		return $this;
	}
	
	public function withEnclosure(string $enclosure): static
	{
		$this->enclosure = $enclosure;
		
		return $this;
	}
	
	public function withoutBom(): static
	{
		$this->bom = false;
		
		return $this;
	}
	
	public function withEmptyNewLineAtEndOfFile(): static
	{
		$this->empty_new_line = true;
		
		return $this;
	}
	
	public function withoutEmptyNewLineAtEndOfFile(): static
	{
		$this->empty_new_line = false;
		
		return $this;
	}
	
	public function getIterator(?string $path = null): WriteIterator
	{
		$path ??= tempnam_with_cleanup();
		
		return new WriteIterator(
			path: $path,
			generator: $this->rows(),
			writer: $this->writer(),
			cleanup: $this->cleanupCallback(),
		);
	}
	
	protected function writer(): WriterInterface
	{
		$options = new OpenSpout\Options();
		$options->FIELD_DELIMITER = $this->delimiter;
		$options->FIELD_ENCLOSURE = $this->enclosure;
		$options->SHOULD_ADD_BOM = $this->bom;
		
		return new OpenSpout\Writer($options);
	}
	
	protected function cleanupCallback(): ?Closure
	{
		if (! $this->empty_new_line) {
			return fn($path) => file_put_contents($path, rtrim(file_get_contents($path), PHP_EOL));
		}
		
		return null;
	}
}
