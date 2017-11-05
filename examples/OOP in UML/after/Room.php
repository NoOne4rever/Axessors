<?php

use Axessors\Axessors;

/**
 * Class Room.
 *
 * Employee's room.
 *
 * @method int getNumber() getter for number
 * @method void setNumber(int $number) setter for number
 */
class Room
{
    use Axessors;

    /** @var int room number */
    private $number; #> +axs int

    /**
     * Room constructor.
     *
     * @param int $number room number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }
}