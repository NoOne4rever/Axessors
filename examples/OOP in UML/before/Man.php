<?php

/**
 * Class Man.
 * 
 * Stores information about a man.
 */
class Man
{
    /** @var string name */
	private $name;
	/** @var string second name */
	private $surname;

    /**
     * Man constructor.
     * 
     * @param string $name name
     * @param string $surname second name
     */
	public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    /**
     * Getter for name.
     * 
     * @see Man::$name
     * 
     * @return string name
     */
    public function getName(): string
	{
		return $this->name;
	}

    /**
     * Getter for second name.
     * 
     * @see Man::$surname
     * 
     * @return string surname
     */
	public function getSurname(): string
	{
		return $this->surname;
	}

    /**
     * Setter for first name.
     * 
     * @see Man::$surname
     * 
     * @param string $name first name
     */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

    /**
     * Setter for second name.
     * 
     * @see Man::$surname
     * 
     * @param string $surname second name
     */
	public function setSurname(string $surname): void
	{
		$this->surname = $surname;
	}
}