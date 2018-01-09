<?php

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

class IdCard
{
    use Axessors;

    private $number; #: +axs int
    private $dateExpire; #: +wrt int +rdb -> `$var = date('d.m.Y', $var)`

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}