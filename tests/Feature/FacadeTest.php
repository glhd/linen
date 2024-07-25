<?php

namespace Feature;

use Glhd\Linen\ExcelReader;
use Glhd\Linen\Facades\Linen;
use Glhd\Linen\Tests\TestCase;

class FacadeTest extends TestCase
{
	public function test_it_can_read_a_basic_csv_file_via_the_facade(): void
	{
		$read = Linen::read($this->fixture('basic.csv'));
		
		foreach ($read as $index => $row) {
			$this->assertSame(match ($index) {
				0 => ['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
				1 => ['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
			}, $row->toArray());
		}
	}
	
	public function test_it_can_read_a_basic_excel_file_via_the_facade(): void
	{
		$read = Linen::read($this->fixture('basic.xlsx'));
		
		foreach ($read as $index => $row) {
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
	
	public function test_it_can_write_a_basic_csv_file_via_the_facade(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$path = tempnam(sys_get_temp_dir(), 'glhd-linen-data').'.csv';
		
		$written = file_get_contents(Linen::write($data, $path));
		$expected = <<<CSV
		﻿"User Id",Name,Nullable,Number
		1,Chris,,40.2
		10,Bogdan,"not null",-37
		CSV;
		
		$this->assertSame($expected, $written);
		
		unlink($path);
	}
	
	public function test_it_can_write_a_basic_excel_file_via_the_facade(): void
	{
		$data = [
			['user_id' => 1, 'name' => 'Chris', 'nullable' => null, 'number' => 40.2],
			['user_id' => 10, 'name' => 'Bogdan', 'nullable' => 'not null', 'number' => -37],
		];
		
		$path = tempnam(sys_get_temp_dir(), 'glhd-linen-data').'.xlsx';
		
		Linen::write($data, $path);
		
		$read = ExcelReader::read($path)->toArray();
		
		$this->assertSame($data, $read);
		
		@unlink($path);
	}
}
