<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\After;

use NoOne4rever\Axessors\Axessors;

class PastPosition
{
    use Axessors;
    
    private $name; #: +axs string
    private $departament; #: +axs Departament

    public function __construct(string $position, Departament $departament)
    {
        $this->name = $position;
        $this->departament = $departament;
    }
}