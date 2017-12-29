<?php

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

class PastPosition
{
    use Axessors;

    private $name; #: +axs string
    private $departament; #: +axs Departament

    public function __construct(string $name, Departament $departament)
    {
        $this->name = $name;
        $this->departament = $departament;
    }
}