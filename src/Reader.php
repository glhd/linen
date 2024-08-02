<?php

namespace Glhd\Linen;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use IteratorAggregate;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\ReaderInterface;
use Traversable;
use UnexpectedValueException;

/** @extends IteratorAggregate<int, Collection> */
abstract class Reader implements IteratorAggregate
{
	public static function from(string $path): static
	{
		return new static($path);
	}
	
	public static function read(string $path): LazyCollection
	{
		return static::from($path)->collect();
	}
	
	public function __construct(
		protected string $path,
	) {
	}
	
	public function getIterator(): Traversable
	{
		return $this->collect();
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
						/** @var \OpenSpout\Common\Entity\Row $row */
						if (null === $keys) {
							$keys = array_map($this->headerToKey(...), $row->toArray());
							$columns = count($keys);
							continue;
						}
						
						$data = $this->castRow($row);
						$data_columns = count($data);
						
						if ($columns < $data_columns) {
							foreach (range(1, $data_columns) as $index => $column) {
								$keys[$index] ??= "column{$column}";
							}
							$columns = count($keys);
						}
						
						if ($columns > $data_columns) {
							$data = array_merge($data, array_fill(0, $columns - $data_columns, null));
						}
						
						yield Collection::make(array_combine($keys, $data));
					}
				}
			} finally {
				$reader->close();
			}
		});
	}
	
	abstract protected function reader(): ReaderInterface;
	
	protected function castRow(Row $data): array
	{
		return array_map($this->castCell(...), $data->getCells());
	}
	
	protected function castCell(Cell $cell): mixed
	{
		return $cell->getValue();
	}
	
	protected function headerToKey(string $value): string
	{
		return Str::snake(strtolower($value));
	}
}
