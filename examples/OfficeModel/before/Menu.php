<?php

class Menu
{
    public static function showEmployees(array $employees): void
    {
        echo "List of employees:";
        foreach ($employees as $employee) {
            if ($employee instanceof Employee) {
                echo PHP_EOL . "{$employee->getName()} - {$employee->getPosition()}";
            }
        }
        echo '.' . PHP_EOL;
    }
}