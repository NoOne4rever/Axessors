<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\After;

use NoOne4rever\Axessors\Axessors;

class Employee extends Man
{
    use Axessors;

    private $idCard; #: +axs IdCard
    private $dp; #: +axs Departament -> `{$.dp && $.dp->deleteEmployee($this); $var->addEmployee($this);}` => departament
    private $rooms; #: +axs Array[Room] => room
    private $position; #: +axs string -> `$.addPastPosition(new :PastPosition($.position, $.dp))`
    private $pastPositions; #: +axs Array[PastPosition] => pastPosition

    public function __construct(string $name, string $surname, string $position)
    {
        parent::__construct($name, $surname);
        $this->position = $position;
    }
}