<?php

namespace Glhd\Linen\Tests\Feature;

use Glhd\Linen\CsvReader;
use Glhd\Linen\Tests\TestCase;

class CsvReaderTest extends TestCase
{
	public function test_it_can_read_a_basic_csv_file_as_an_iterator(): void
	{
		$reader = CsvReader::from($this->fixture('basic.csv'));
		
		foreach ($reader as $index => $row) {
			$this->assertSame(match ($index) {
				0 => ['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
				1 => ['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
			}, $row->toArray());
		}
	}
	
	public function test_it_can_read_a_basic_csv_file_as_a_collection(): void
	{
		$collection = CsvReader::from($this->fixture('basic.csv'))->collect();
		
		foreach ($collection as $index => $row) {
			$this->assertSame(match ($index) {
				0 => ['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
				1 => ['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
			}, $row->toArray());
		}
	}
	
	public function test_if_headers_are_missing_column_numbers_are_used_as_keys(): void
	{
		$collection = CsvReader::from($this->fixture('more-columns-than-headers.csv'))->collect();
		
		foreach ($collection as $index => $row) {
			$this->assertSame(match ($index) {
				0 => ['user_id' => 1, 'name' => 'Chris', 'column3' => null, 'column4' => 40.2, 'column5' => null, 'column6' => null, 'column7' => null],
				1 => ['user_id' => 10, 'name' => 'Bogdan', 'column3' => 'not null', 'column4' => -37, 'column5' => null, 'column6' => null, 'column7' => null],
			}, $row->toArray());
		}
	}
}
