<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\After;

use NoOne4rever\Axessors\Axessors;

class Man
{
    use Axessors;
    
    private $name, $surname; #: +axs string

    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }
}