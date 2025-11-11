<?php

namespace Glhd\Linen\Support;

use Closure;
use Generator;
use Iterator;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\WriterInterface;

class WriteIterator implements Iterator
{
	protected int $written = 0;
	
	protected bool $open = false;
	
	protected bool $pending = false;
	
	public function __construct(
		public readonly string $path,
		protected Generator $generator,
		protected WriterInterface $writer,
		protected ?Closure $cleanup = null,
	) {
	}
	
	public function drain(): string
	{
		if (! $this->open) {
			$this->rewind();
		}
		
		while ($this->valid()) {
			$this->writeCurrentRow();
			$this->next();
		}
		
		return $this->path;
	}
	
	public function rewind(): void
	{
		$this->written = 0;
		$this->pending = true;
		
		$this->generator->rewind();
		
		$this->openWriter();
	}
	
	public function key(): mixed
	{
		return $this->generator->key();
	}
	
	/**
	 * To prevent unintended memory issues, we're only going to return the write count
	 * from the iterator. The iterator is just for handling progress/stepping, and not
	 * for accessing the underlying data.
	 *
	 * @return int
	 */
	public function current(): int
	{
		$this->writeCurrentRow();
		
		return $this->written;
	}
	
	public function next(): void
	{
		if (! $this->open) {
			return;
		}
		
		$this->generator->next();
		
		$this->pending = true;
	}
	
	public function valid(): bool
	{
		if (! $valid = $this->generator->valid()) {
			$this->closeWriter();
		}
		
		return $valid;
	}
	
	public function __destruct()
	{
		$this->closeWriter();
	}
	
	protected function openWriter(): void
	{
		if (! $this->open) {
			$this->writer->openToFile($this->path);
			$this->open = true;
		}
	}
	
	protected function writeCurrentRow(): void
	{
		if ($this->pending) {
			$this->writer->addRow(
				Row::fromValues($this->generator->current()->toArray())
			);
			
			$this->written++;
			$this->pending = false;
		}
	}
	
	protected function closeWriter(): void
	{
		if ($this->open) {
			$this->writer->close();
			
			if ($this->cleanup) {
				call_user_func($this->cleanup, $this->path);
			}
			
			$this->open = false;
		}
	}
}
