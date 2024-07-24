<?php

namespace Glhd\Linen;

use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\XLSX as OpenSpout;

class ExcelWriter extends Writer
{
	protected function writer(): WriterInterface
	{
		return new OpenSpout\Writer(new OpenSpout\Options());
	}
}
