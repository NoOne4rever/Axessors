<?php

use Axessors\Axessors;

class Man
{
	use Axessors;

	protected $name; #> +axs string
	protected $surname; #> +axs string
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function getSurname(): string
	{
		return $this->surname;
	}
	
	public function setName(string $name): void
	{
		$this->name = $name;
	}
	
	public function setSurname(string $surname): void
	{
		$this->surname = $surname;
	}
}