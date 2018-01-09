<?php

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

class Departament implements Unit
{
    use Axessors;

    private $name; #: +axs string
    private $employees = []; #: +axs Array[Employee]

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}