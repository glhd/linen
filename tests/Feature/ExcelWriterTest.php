<?php

namespace Feature;

use Glhd\Linen\ExcelReader;
use Glhd\Linen\ExcelWriter;
use Glhd\Linen\Tests\TestCase;

class ExcelWriterTest extends TestCase
{
	public function test_it_can_write_to_an_excel_file(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$tempfile = ExcelWriter::for($data)->writeToTemporaryFile();
		
		$read = ExcelReader::read($tempfile)->toArray();
		
		$this->assertSame($data, $read);
	}
	
	public function test_it_can_write_to_an_excel_file_with_iterator(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$iterator = ExcelWriter::for($data)->getIterator();
		
		$this->assertEquals(
			[0 => 1, 1 => 2, 2 => 3],
			iterator_to_array($iterator),
		);
		
		$read = ExcelReader::read($iterator->path)->toArray();
		
		$this->assertSame($data, $read);
	}
}
