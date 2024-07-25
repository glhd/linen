<?php

namespace Feature;

use Glhd\Linen\CsvReader;
use Glhd\Linen\ExcelReader;
use Glhd\Linen\Tests\TestCase;

class ExcelReaderTest extends TestCase
{
	public function test_it_can_read_a_basic_excel_file_as_an_iterator(): void
	{
		$reader = ExcelReader::from($this->fixture('basic.xlsx'));
		
		foreach ($reader as $index => $row) {
			if (0 === $index) {
				$this->assertEquals(1, $row['user_id']);
				$this->assertEquals('Chris', $row['name']);
				$this->assertEquals('2024-07-25', $row['date']->format('Y-m-d'));
				$this->assertEquals(40.20, $row['number']);
			} elseif (1 === $index) {
				$this->assertEquals(10, $row['user_id']);
				$this->assertEquals('Bogdan', $row['name']);
				$this->assertEquals('2024-07-20', $row['date']->format('Y-m-d'));
				$this->assertEquals(-37.0, $row['number']);
			}
		}
	}
	
	public function test_it_can_read_a_basic_excel_file_as_a_collection(): void
	{
		$collection = ExcelReader::from($this->fixture('basic.xlsx'))->collect();
		
		foreach ($collection as $index => $row) {
			if (0 === $index) {
				$this->assertEquals(1, $row['user_id']);
				$this->assertEquals('Chris', $row['name']);
				$this->assertEquals('2024-07-25', $row['date']->format('Y-m-d'));
				$this->assertEquals(40.20, $row['number']);
			} elseif (1 === $index) {
				$this->assertEquals(10, $row['user_id']);
				$this->assertEquals('Bogdan', $row['name']);
				$this->assertEquals('2024-07-20', $row['date']->format('Y-m-d'));
				$this->assertEquals(-37.0, $row['number']);
			}
		}
	}
}
