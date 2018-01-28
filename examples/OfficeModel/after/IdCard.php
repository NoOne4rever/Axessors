<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\After;

use NoOne4rever\Axessors\Axessors;

class IdCard
{
    use Axessors;

    private const EXPIRATION_TIME = 86400;
    private const DATE_FORMAT = 'd/m/Y';

    private $number; #: +axs int
    private $dateExpire; #: +wrt int +rdb -> `$var = date(self::DATE_FORMAT, $var)` => expirationDate

    public function __construct(int $number)
    {
        $this->number = $number;
        $this->dateExpire = time() + self::EXPIRATION_TIME;
    }
}