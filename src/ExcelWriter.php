<?php

namespace Glhd\Linen;

use Illuminate\Support\Collection;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer as OpenSpoutWriter;

class ExcelWriter extends Writer
{
	public function write(string $path): string
	{
		$options = new Options();
		
		$writer = new OpenSpoutWriter($options);
		
		$writer->openToFile($path);
		
		/** @var Collection $first_row */
		$first_row = current(iterator_to_array($this->rows()));
		$writer->addRow(Row::fromValues($first_row->keys()->all()));
		
		foreach ($this->rows() as $row) {
			$writer->addRow(Row::fromValues($row->toArray()));
		}
		
		$writer->close();
		
		return $path;
	}
}
