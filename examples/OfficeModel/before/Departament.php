<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\Before;

class Departament implements Unit
{
    private $name;
    private $employees;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getPersonCount(): int
    {
        return count($this->employees);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
        $employee->setDepartament($this);
    }

    public function deleteEmployee(Employee $employeeToDelete): void
    {
        foreach ($this->employees as &$employee) {
            if ($employee === $employeeToDelete) {
                unset($employee);
            }
        }
    }
}