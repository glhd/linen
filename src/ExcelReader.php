<?php

namespace Glhd\Linen;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use OpenSpout\Reader\XLSX\Reader as OpenSpoutReader;
use UnexpectedValueException;

class ExcelReader extends Reader
{
	public function collect(): LazyCollection
	{
		return new LazyCollection(function() {
			$reader = new OpenSpoutReader();
			$reader->open($this->path);
			
			try {
				foreach ($reader->getSheetIterator() as $sheet) {
					$columns = 0;
					$headers = null;
					
					foreach ($sheet->getRowIterator() as $row) {
						if (null === $headers) {
							$headers = array_map($this->header(...), $row->toArray());
							$columns = count($headers);
							continue;
						}
						
						$data = $row->toArray();
						$data_columns = count($data);
						
						if ($columns < $data_columns) {
							throw new UnexpectedValueException("Expected {$columns} columns of data but got {$data_columns}");
						}
						
						if ($columns > $data_columns) {
							$data = array_merge($data, array_fill(0, $columns - $data_columns, null));
						}
						
						yield array_combine($headers, $data);
					}
				}
			} finally {
				$reader->close();
			}
		});
	}
	
	protected function header(string $value): string
	{
		// If our header has no lower-case characters, we'll convert it to
		// all lower-case first. This ensures that values like "NACHI ID" turn
		// into "nachi_id" rather than "N_A_C_H_I_I_D".
		if (! preg_match('/[a-z]/', $value)) {
			$value = strtolower($value);
		}
		
		return Str::snake($value);
	}
}
