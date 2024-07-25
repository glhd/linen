<?php

namespace Feature;

use Glhd\Linen\CsvReader;
use Glhd\Linen\CsvWriter;
use Glhd\Linen\Tests\TestCase;

class CsvWriterTest extends TestCase
{
	public function test_it_can_write_to_a_csv(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$written = file_get_contents(CsvWriter::for($data)->writeToTemporaryFile());
		$expected = <<<CSV
		﻿"User Id",Name,Nullable,Number
		1,Chris,,40.2
		10,Bogdan,"not null",-37
		CSV;
		
		$this->assertSame($expected, $written);
	}
}
