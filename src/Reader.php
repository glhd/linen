<?php

namespace Glhd\Linen;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use OpenSpout\Reader\ReaderInterface;
use UnexpectedValueException;

abstract class Reader
{
	abstract protected function reader(): ReaderInterface;
	
	public static function read(string $path): LazyCollection
	{
		return (new static($path))->collect();
	}
	
	public function __construct(
		protected string $path,
	) {
	}
	
	public function collect(): LazyCollection
	{
		return new LazyCollection(function() {
			$reader = $this->reader();
			$reader->open($this->path);
			
			try {
				foreach ($reader->getSheetIterator() as $sheet) {
					$columns = 0;
					$keys = null;
					
					foreach ($sheet->getRowIterator() as $row) {
						if (null === $keys) {
							$keys = array_map($this->headerToKey(...), $row->toArray());
							$columns = count($keys);
							continue;
						}
						
						$data = $row->toArray();
						$data_columns = count($data);
						
						if ($columns < $data_columns) {
							// TODO: Offer option to trim
							throw new UnexpectedValueException("Expected {$columns} columns of data but got {$data_columns}");
						}
						
						if ($columns > $data_columns) {
							$data = array_merge($data, array_fill(0, $columns - $data_columns, null));
						}
						
						yield array_combine($keys, $data);
					}
				}
			} finally {
				$reader->close();
			}
		});
	}
	
	protected function headerToKey(string $value): string
	{
		return Str::snake(strtolower($value));
	}
}
