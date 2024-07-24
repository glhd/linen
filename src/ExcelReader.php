<?php

namespace Glhd\Linen;

use OpenSpout\Reader\ReaderInterface;
use OpenSpout\Reader\XLSX as OpenSpout;

class ExcelReader extends Reader
{
	protected function reader(): ReaderInterface
	{
		return new OpenSpout\Reader();
	}
}
