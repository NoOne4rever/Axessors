<?php

class PastPosition
{
    private $name;
    private $departament;

    public function __construct(string $name, Departament $departament)
    {
        $this->name = $name;
        $this->departament = $departament;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDepartament(): Departament
    {
        return $this->departament;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDepartament(Departament $departament): void
    {
        $this->departament = $departament;
    }
}