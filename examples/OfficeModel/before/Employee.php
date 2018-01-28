<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\Before;

class Employee extends Man
{
    private $idCard;
    private $departament;    
    private $rooms;
    private $position;
    private $pastPositions;

    public function __construct(string $name, string $surname, string $position)
    {
        parent::__construct($name, $surname);
        $this->position = $position;
    }

    public function getIdCard(): IdCard
    {
        return $this->idCard;
    }

    public function setIdCard(IdCard $idCard): void
    {
        $this->idCard = $idCard;
    }

    public function getDepartament(): Departament
    {
        return $this->departament;
    }

    public function setDepartament(Departament $departament)
    {
        $this->departament = $departament;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms[] = $room;
    }

    public function deleteRoom(Room $roomToDelete): void
    {
        foreach ($this->rooms as &$room) {
            if ($room === $roomToDelete) {
                unset($room);
            }
        }
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->addPastPosition(new PastPosition($this->position, $this->departament));
        $this->position = $position;
    }

    public function getPastPositions(): array
    {
        return $this->pastPositions;
    }

    public function addPastPosition(PastPosition $position): void
    {
        $this->pastPositions[] = $position;
    }

    public function deletePastPosition(PastPosition $positionToDelete): void
    {
        foreach ($this->pastPositions as &$position) {
            if ($position === $positionToDelete) {
                unset($position);
            }
        }
    }
}