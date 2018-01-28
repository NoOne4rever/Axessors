<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\After;

class Menu
{
    public function showEmployees(array $employees): void
    {
        echo 'Employees list:';
        foreach ($employees as $employee) {
            echo sprintf(
                '%s + %s %s, %s',
                PHP_EOL,
                $employee->getName(),
                $employee->getSurname(),
                $employee->getPosition()
            );
        }
        echo '.' . PHP_EOL;
    }
}