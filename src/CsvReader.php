<?php

namespace Glhd\Linen;

use OpenSpout\Reader\ReaderInterface;

class CsvReader extends Reader
{
	protected function reader(): ReaderInterface
	{
		return new \OpenSpout\Reader\CSV\Reader();
	}
}
