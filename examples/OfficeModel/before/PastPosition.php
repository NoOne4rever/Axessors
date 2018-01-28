<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\Before;

class PastPosition
{
    private $name;
    private $departament;

    public function __construct(string $position, Departament $departament)
    {
        $this->name = $position;
        $this->departament = $departament;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDepartament(): Departament
    {
        return $this->departament;
    }

    public function setDepartament(Departament $departament): void
    {
        $this->departament = $departament;
    }
}