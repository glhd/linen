<?php

namespace Feature;

use Glhd\Linen\CsvWriter;
use Glhd\Linen\Tests\TestCase;
use function Glhd\Linen\tempnam_with_cleanup;

class CsvWriterTest extends TestCase
{
	public function test_it_can_write_to_a_csv(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$path = CsvWriter::for($data)->writeToTemporaryFile();
		
		$written = file_get_contents($path);
		$expected = <<<CSV
		﻿"User Id",Name,Nullable,Number
		1,Chris,,40.2
		10,Bogdan,"not null",-37
		CSV;
		
		$this->assertSame($expected, $written);
	}
	
	public function test_it_can_write_to_a_csv_with_a_new_line_at_end(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$path = CsvWriter::for($data)->withEmptyNewLineAtEndOfFile()->writeToTemporaryFile();
		
		$written = file_get_contents($path);
		$expected = <<<CSV
		﻿"User Id",Name,Nullable,Number
		1,Chris,,40.2
		10,Bogdan,"not null",-37
		
		CSV;
		
		$this->assertSame($expected, $written);
	}
	
	public function test_it_can_write_with_an_iterator(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris'],
			['user_id' => 10, 'name' => 'Skyler'],
		];
		
		$iterator = CsvWriter::for($data)->getIterator(tempnam_with_cleanup());
		
		// Iterator returns [original key] => [rows written]
		$this->assertEquals(
			[0 => 1, 1 => 2, 2 => 3],
			iterator_to_array($iterator)
		);
		
		$written = file_get_contents($iterator->path);
		$expected = <<<CSV
		﻿"User Id",Name
		1,Chris
		10,Skyler
		CSV;
		
		$this->assertSame($expected, $written);
	}
}
