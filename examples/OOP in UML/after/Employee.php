<?php

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

class Employee extends Man
{
    use Axessors;

    private $position; #: +axs string -> `$this->addPastPosition(new :PastPosition($this->position, $this->departament))`
    private $idCard; #: +axs IdCard
    private $room = []; #: +axs Array[Room]
    private $departament; #: +axs Departament
    private $pastPosition = []; #: +axs Array[PastPosition]

    public function __construct(string $name, string $surname, string $position, Departament $departament)
    {
        parent::__construct($name, $surname);
        $this->position = $position;
        $this->departament = $departament;
    }
}