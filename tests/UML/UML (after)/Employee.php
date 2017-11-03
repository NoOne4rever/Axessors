<?php

use Axessors\Axessors;

class Employee extends Man
{
	use Axessors;

	private $position; #> +axs string >> `$this->addPastPosition(new PastPosition($this->position, $this->departament))`
	private $idCard; #> +axs IdCard
	private $room = []; #> +axs Array[Room]
	private $departament; #> +axs Departament
	private $pastPosition = []; #> +axs Array
	
	public function __construct(string $name, string $surname, string $position, Departament $departament)
	{
		$this->name        = $name;
		$this->surname     = $surname;
		$this->position    = $position;
		$this->departament = $departament;
	}
}