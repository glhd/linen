<?php

namespace Glhd\Linen;

use Carbon\CarbonImmutable;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Reader\ReaderInterface;

class ExcelReader extends Reader
{
	protected function reader(): ReaderInterface
	{
		return new \OpenSpout\Reader\XLSX\Reader();
	}
	
	protected function castCell(Cell $cell): mixed
	{
		if ($cell instanceof Cell\DateTimeCell) {
			return CarbonImmutable::createFromInterface($cell->getValue());
		}
		
		if ($cell instanceof Cell\EmptyCell) {
			return null;
		}
		
		return parent::castCell($cell);
	}
}
