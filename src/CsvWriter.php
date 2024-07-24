<?php

namespace Glhd\Linen;

use OpenSpout\Writer\CSV as OpenSpout;
use OpenSpout\Writer\WriterInterface;

class CsvWriter extends Writer
{
	protected string $delimiter = ',';
	
	protected string $enclosure = '"';
	
	protected bool $bom = true;
	
	protected bool $empty_new_line = true;
	
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
	
	public function withoutEmptyNewLineAtEndOfFile(): static
	{
		$this->empty_new_line = false;
		
		return $this;
	}
	
	public function write(string $path): string
	{
		parent::write($path);
		
		if ($this->empty_new_line) {
			file_put_contents($path, rtrim(file_get_contents($path), PHP_EOL));
		}
		
		return $path;
	}
	
	protected function writer(): WriterInterface
	{
		$options = new OpenSpout\Options();
		$options->FIELD_DELIMITER = $this->delimiter;
		$options->FIELD_ENCLOSURE = $this->enclosure;
		$options->SHOULD_ADD_BOM = $this->bom;
		
		return new OpenSpout\Writer($options);
	}
}
