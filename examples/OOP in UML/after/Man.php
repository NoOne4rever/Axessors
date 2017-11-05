<?php

use Axessors\Axessors;

/**
 * Class Man.
 *
 * Stores information about a man.
 *
 * @method string getName() getter for name
 * @method string getSurname() getter for surname
 * @method void setName(string $name) setter for name
 * @method void setSurname(string $name) setter for surname
 */
class Man
{
    use Axessors;

    /** @var string name */
    protected $name; #> +axs string
    /** @var string second name */
    protected $surname; #> +axs string

    /**
     * Man constructor.
     *
     * @param string $name name
     * @param string $surname second name
     */
    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }
}