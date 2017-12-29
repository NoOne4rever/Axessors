<?php

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

class Room
{
    use Axessors;

    private $number; #: +axs int

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}