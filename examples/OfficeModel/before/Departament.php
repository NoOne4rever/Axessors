<?php

class Departament implements Unit
{
    private $name;
    private $employees = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addEmployee(Employee $employee): void
    {
        $this->employees[$employee->getIdCard()->getNumber()] = $employee;
        $employee->setDepartament($this);
    }

    public function removeEmployee(Employee $employee): void
    {
        unset($this->employees[$employee->getIdCard()->getNumber()]);
    }

    public function getPersonCount(): int
    {
        return count($this->employees);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
