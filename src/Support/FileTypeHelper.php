<?php

namespace Glhd\Linen\Support;

use Generator;
use Glhd\Linen\CsvReader;
use Glhd\Linen\CsvWriter;
use Glhd\Linen\ExcelReader;
use Glhd\Linen\ExcelWriter;
use Glhd\Linen\Reader;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Enumerable;
use InvalidArgumentException;
use Symfony\Component\Mime\MimeTypes;

class FileTypeHelper
{
	public function read(string $path): Reader
	{
		$mime = MimeTypes::getDefault()->guessMimeType($path);
		
		return match ($mime) {
			'application/msexcel',
			'application/x-msexcel',
			'zz-application/zz-winassoc-xls',
			'application/vnd.ms-excel',
			'application/vnd.ms-excel.sheet.binary.macroenabled.12',
			'application/vnd.ms-excel.sheet.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.template' => ExcelReader::from($path),
			'application/csv',
			'text/csv',
			'text/csv-schema',
			'text/x-comma-separated-values',
			'text/x-csv',
			'text/plain' => CsvReader::from($path),
			default => throw new InvalidArgumentException("Unable to infer file type for '{$path}'"),
		};
	}
	
	public function write(array|Enumerable|Generator|Builder $data, string $path): string
	{
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		
		$writer = match ($extension) {
			'xlsx', 'xls' => ExcelWriter::for($data),
			'csv' => CsvWriter::for($data),
			default => throw new InvalidArgumentException("Unable to infer file type for '{$path}'"),
		};
		
		return $writer->write($path);
	}
}
