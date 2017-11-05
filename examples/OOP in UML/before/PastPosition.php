<?php

/**
 * Class PastPosition.
 * 
 * Stores information about employee's position.
 */
class PastPosition
{
    /** @var string position name */
	private $name;
	/** @var Departament departament */
	private $departament;

    /**
     * PastPosition constructor.
     * 
     * @param string $name position name
     * @param Departament $departament departament
     */
	public function __construct(string $name, Departament $departament)
	{
		$this->name        = $name;
		$this->departament = $departament;
	}

    /**
     * Getter for name.
     * 
     * @see PastPosition::$name
     * 
     * @return string position name
     */
	public function getName(): string
	{
		return $this->name;
	}

    /**
     * Getter for departament.
     * 
     * @see PastPosition::$departament
     * 
     * @return Departament departament
     */
	public function getDepartament(): Departament
	{
		return $this->departament;
	}

    /**
     * Setter for name.
     * 
     * @see PastPosition::$name
     * 
     * @param string $name new name
     */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

    /**
     * Setter for departament.
     * 
     * @see PastPosition::$departament
     * 
     * @param Departament $departament new departament
     */
	public function setDepartament(Departament $departament): void
	{
		$this->departament = $departament;
	}
}