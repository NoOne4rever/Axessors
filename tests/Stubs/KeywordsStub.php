<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests\Stubs;

use NoOne4rever\Axessors\Axessors;

/**
 * Class KeywordsStub.
 *
 * @package NoOne4rever\Axessors\Tests\Stubs
 *
 * @method string getShortRdb() getter for KeywordsStub::$shortRdb
 * @method string getShortAxs() getter for KeywordsStub::$shortAxs
 * @method string getFullRdb() getter for KeywordsStub::$fullRdb
 * @method string getFullAxs() getter for KeywordsStub::$fullAxs
 * @method void setShortWrt(string $val) setter for KeywordsStub::$shortWrt
 * @method void setShortAxs(string $val) setter for KeywordsStub::$shortAxs
 * @method void setFullWrt(string $val) setter for KeywordsStub::$fullWrt
 * @method void setFullAxs(string $val) setter for KeywordsStub::$fullAxs
 */
class KeywordsStub extends NonAxessorsStub
{
    use Axessors;

    /** @var string with short rdb keyword */
    public $shortRdb = 'short rdb'; #: +rdb
    /** @var string with short wrt keyword */
    public $shortWrt = 'short wrt'; #: +wrt
    /** @var string with short axs keyword */
    public $shortAxs = 'short axs'; #: +axs
    /** @var string with full rdb keyword */
    public $fullRdb = 'full rdb'; #: +readable
    /** @var string with full wrt keyword */
    public $fullWrt = 'full wrt'; #: +writable
    /** @var string with full axs keyword */
    public $fullAxs = 'full axs'; #: +accessible
}