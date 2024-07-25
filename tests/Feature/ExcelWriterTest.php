<?php

namespace Feature;

use Glhd\Linen\CsvWriter;
use Glhd\Linen\ExcelReader;
use Glhd\Linen\ExcelWriter;
use Glhd\Linen\Tests\TestCase;

class ExcelWriterTest extends TestCase
{
	public function test_it_can_write_to_a_excel_file(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$tempfile = ExcelWriter::for($data)->writeToTemporaryFile();
		
		$read = ExcelReader::read($tempfile)->toArray();
		
		$this->assertSame($data, $read);
	}
}
