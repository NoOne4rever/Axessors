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
 * @method string getShortRdb()
 * @method string getShortAxs()
 * @method string getFullRdb()
 * @method string getFullAxs()
 */
class KeywordsStub
{
    use Axessors;

    public $shortRdb = 'short rdb'; #> +rdb
    public $shortWrt = 'short wrt'; #> +wrt
    public $shortAxs = 'short axs'; #> +axs
    public $fullRdb = 'full rdb'; #> +readable
    public $fullWrt = 'full wrt'; #> +writable
    public $fullAxs = 'full axs'; #> +accessible
}