<?php

use Axessors\Axessors;

/**
 * Class PastPosition.
 *
 * Stores information about employee's position.
 *
 * @method string getName() getter for name
 * @method Departament getDepartament() getter for departament
 * @method void setName(string $name) setter for name
 * @method void setDepartament(Departament $departament) setter for departament
 */
class PastPosition
{
    use Axessors;

    /** @var string position name */
    private $name; #> +axs string
    /** @var Departament departament */
    private $departament; #> +axs Departament

    /**
     * PastPosition constructor.
     *
     * @param string $name position name
     * @param Departament $departament departament
     */
    public function __construct(string $name, Departament $departament)
    {
        $this->name = $name;
        $this->departament = $departament;
    }
}