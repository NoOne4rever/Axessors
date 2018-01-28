<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\After;

use NoOne4rever\Axessors\Axessors;

class Room
{
    use Axessors;

    private $number; #: +axs int > 0

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}