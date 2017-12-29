<?php

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

class Man
{
    use Axessors;

    protected $name; #: +axs string
    protected $surname; #: +axs string

    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }
}