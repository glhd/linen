<?php

namespace Glhd\Linen;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Reader\ReaderInterface;

class CsvReader extends Reader
{
	protected function reader(): ReaderInterface
	{
		return new \OpenSpout\Reader\CSV\Reader();
	}
	
	protected function castCell(Cell $cell): mixed
	{
		$value = $cell->getValue();
		
		return match (true) {
			is_numeric($value) => (float) $value == (int) $value ? (int) $value : (float) $value,
			'' === $value => null,
			default => $value,
		};
	}
}
