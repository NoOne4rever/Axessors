<?php

/**
 * Class Departament.
 * 
 * Stores employees.
 */
class Departament implements Unit
{
    /** @var string departament name */
	private $name;
	/** @var Employee[] departament employees */
	private $employees = [];

    /**
     * Departament constructor.
     * 
     * @param string $name departament name
     */
	public function __construct(string $name)
	{
		$this->name = $name;
	}

    /**
     * Adds an employee to the departament.
     * 
     * @param Employee $employee new employee
     */
	public function addEmployee(Employee $employee): void
	{
		$this->employees[$employee->getIdCard()->getNumber()] = $employee;
		$employee->setDepartament($this);
	}

    /**
     * Removes an employee from the departament.
     * 
     * @param Employee $employee employee to remove
     */
	public function removeEmployee(Employee $employee): void
	{
		unset($this->employees[$employee->getIdCard()->getNumber()]);
	}

    /**
     * Counts departament employees.
     * 
     * @return int number of employees
     */
	public function getPersonCount(): int
	{
		return count($this->employees);
	}

    /**
     * Getter for departament name.
     * 
     * @see Departament::$name
     * 
     * @return string departament name
     */
	public function getName(): string
	{
		return $this->name;
	}

    /**
     * Getter for employees.
     * 
     * @see Departament::$employees
     * 
     * @return Employee[] departament employees
     */
	public function getEmployees(): array
	{
		return $this->employees;
	}

    /**
     * Setter for departament name.
     * 
     * @see Departament::$name
     * 
     * @param string $name new departament name
     */
	public function setName(string $name): void
	{
		$this->name = $name;
	}
}