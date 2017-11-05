<?php

/**
 * Class Employee.
 * 
 * Stores information about employee.
 */
class Employee extends Man
{
    /** @var string employee's position */
	private $position;
	/** @var IdCard employee's id card */
	private $idCard;
	/** @var Room[] rooms */
	private $room = [];
	/** @var Departament employee's departament */
	private $departament;
	/** @var PastPosition[] past positions */
	private $pastPosition = [];

    /**
     * Employee constructor.
     * 
     * @param string $name name
     * @param string $surname second name
     * @param string $position employee's position
     */
	public function __construct(string $name, string $surname, string $position)
	{
	    parent::__construct($name, $surname);
		$this->position = $position;
	}

    /**
     * Deletes employee's room.
     * 
     * @param Room $room room to delete
     */
	public function deleteRoom(Room $room): void
	{
		unset($this->room[$room->getNumber()]);
	}

    /**
     * Deletes past position.
     * 
     * @param PastPosition $pastPosition position to delete
     */
	public function deletePastPosition(PastPosition $pastPosition): void
	{
		unset($this->pastPosition[$pastPosition->getName()]);
	}

    /**
     * Getter for position.
     * 
     * @see Employee::$position
     * 
     * @return string position
     */
	public function getPosition(): string
	{
		return $this->position;
	}

    /**
     * Getter for id card.
     * 
     * @see Employee::$idCard
     * 
     * @return IdCard id card
     */
	public function getIdCard(): IdCard
	{
		return $this->idCard;
	}

    /**
     * Getter for employee's rooms.
     * 
     * @see Employee::$room
     * 
     * @return Room[] rooms
     */
	public function getRoom(): array
	{
		return $this->room;
	}

    /**
     * Getter for employee's departament.
     * 
     * @see Employee::$departament
     * 
     * @return Departament current departament
     */
	public function getDepartament(): Departament
	{
		return $this->departament;
	}

    /**
     * Getter for past positions.
     * 
     * @see Employee::$pastPosition
     * 
     * @return PastPosition[] past positions
     */
	public function getPastPosition(): array
	{
		return $this->pastPosition;
	}


    /**
     * Setter for position.
     * 
     * @see Employee::$position
     * 
     * @param string $position position to add
     */
	public function setPosition(string $position): void
	{
		$this->setPastPosition(new PastPosition($this->position, $this->departament));
		$this->position = $position;
	}

    /**
     * Setter for id card.
     * 
     * @see Employee::$idCard
     * 
     * @param IdCard $card new id card
     */
	public function setIdCard(IdCard $card): void
	{
		$this->idCard = $card;
	}

    /**
     * Setter for room.
     * 
     * @see Employee::$room
     * 
     * @param Room $room new room
     */
	public function setRoom(Room $room): void
	{
		$this->room[$room->getNumber()] = $room;
	}

    /**
     * Setter for departament.
     * 
     * @see Employee::$departament
     * 
     * @param Departament $departament new departament
     */
	public function setDepartament(Departament $departament): void
	{
		$this->departament = $departament;
	}

    /**
     * Setter for past position.
     * 
     * @see Employee::$pastPosition
     * 
     * @param PastPosition $pastPosition new position
     */
	public function setPastPosition(PastPosition $pastPosition): void
	{
		$this->pastPosition[$pastPosition->getName()] = $pastPosition;
	}
}