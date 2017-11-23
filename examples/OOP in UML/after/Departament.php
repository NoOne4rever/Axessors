<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

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
    private $name; #: +axs string
    /** @var Employee[] departament employees */
    private $employees = []; #: +axs Array[Employee]

    /**
     * Departament constructor.
     *
     * @param string $name departament name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}