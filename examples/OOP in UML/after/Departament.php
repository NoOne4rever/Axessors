<?php

use Axessors\Axessors;

/**
 * Class Departament.
 *
 * Stores employees.
 * 
 * @method void addEmployees(Employee $employee) adds an employee to the departament
 * @method void removeEmployees(Employee $employee) removes an employee
 * @method int countEmployees() returns number of employees
 * @method string getName() getter for departament name
 * @method Employee[] getEmployees() getter for departament employees
 * @method void setName(string $name) setter for name
 */
class Departament implements Unit
{
	use Axessors;

    /** @var string departament name */
	private $name; #> +axs string
    /** @var Employee[] departament employees */
	private $employees = []; #> +axs Array[Employee]
	
	public function __construct(string $name)
	{
		$this->name = $name;
	}
}