<?php

use Axessors\Axessors;

class Room
{
	use Axessors;

	private $number; #> +axs int
	
	public function __construct(int $number)
	{
		$this->number = $number;
	}
}