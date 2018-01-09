<?php

class Employee extends Man
{
    private $position;
    private $idCard;
    private $room = [];
    private $departament;
    private $pastPosition = [];

    public function __construct(string $name, string $surname, string $position)
    {
        parent::__construct($name, $surname);
        $this->position = $position;
    }

    public function deleteRoom(Room $room): void
    {
        unset($this->room[$room->getNumber()]);
    }

    public function deletePastPosition(PastPosition $pastPosition): void
    {
        unset($this->pastPosition[$pastPosition->getName()]);
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getIdCard(): IdCard
    {
        return $this->idCard;
    }

    public function getRoom(): array
    {
        return $this->room;
    }

    public function getDepartament(): Departament
    {
        return $this->departament;
    }

    public function getPastPosition(): array
    {
        return $this->pastPosition;
    }


    public function setPosition(string $position): void
    {
        $this->setPastPosition(new PastPosition($this->position, $this->departament));
        $this->position = $position;
    }

    public function setIdCard(IdCard $card): void
    {
        $this->idCard = $card;
    }

    public function setRoom(Room $room): void
    {
        $this->room[$room->getNumber()] = $room;
    }

    public function setDepartament(Departament $departament): void
    {
        $this->departament = $departament;
    }

    public function setPastPosition(PastPosition $pastPosition): void
    {
        $this->pastPosition[$pastPosition->getName()] = $pastPosition;
    }
}