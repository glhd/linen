<?php

namespace Glhd\Linen;

use Generator;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Enumerable;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Options;
use OpenSpout\Writer\CSV\Writer as OpenSpoutWriter;

class CsvWriter extends Writer
{
	public function __construct(
		protected array|Enumerable|Generator|Builder $data,
		protected bool $headers = true,
		protected string $delimiter = ',',
		protected string $enclosure = '"',
		protected bool $bom = true,
		protected bool $empty_new_line = true,
	) {
		parent::__construct($data, $headers);
	}
	
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
		$options = new Options();
		$options->FIELD_DELIMITER = $this->delimiter;
		$options->FIELD_ENCLOSURE = $this->enclosure;
		$options->SHOULD_ADD_BOM = $this->bom;
		
		$writer = new OpenSpoutWriter($options);
		
		$writer->openToFile($path);
		
		foreach ($this->rows() as $row) {
			$writer->addRow(Row::fromValues($row->toArray()));
		}
		
		$writer->close();
		
		if ($this->empty_new_line) {
			file_put_contents($path, rtrim(file_get_contents($path), PHP_EOL));
		}
		
		return $path;
	}
	
	protected function rows(): Generator
	{
		$source = parent::rows();
		
		$needs_headers = $this->headers;
		
		foreach ($source as $row) {
			if ($needs_headers) {
				$needs_headers = false;
				yield $row->keys()->map($this->header_formatter);
			}
			
			yield $row;
		}
	}
}
