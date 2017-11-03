<?php

class Man
{
	protected $name;
	protected $surname;
	
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