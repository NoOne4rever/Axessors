<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

/**
 * Class IdCard.
 *
 * Employee's id card.
 *
 * @method int getNumber() getter for card number
 * @method void setNumber(int $number) setter for card number
 * @method string getDateExpire() getter for expiration date
 * @method void setDateExpire(int $timestamp) setter for expiration date
 */
class IdCard
{
    use Axessors;

    /** @var int card number */
    private $number; #> +axs int
    /** @var int timestamp of expiration date */
    private $dateExpire; #> +wrt int +rdb >> `$var = date('d.m.Y', $var)`

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}