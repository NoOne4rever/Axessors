<?php

/**
 * Interface Unit.
 *
 * Defines interface for a unit.
 */
interface Unit
{
    /**
     * Counts number of employees.
     *
     * @return int number of employees
     */
    public function getPersonCount(): int;
}