<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors\Tests;

use NoOne4rever\Axessors\Axessors;
use NoOne4rever\Axessors\AxessorsStartup;

require __DIR__ . '/vendor/autoload.php';

/**
 * Class Color.
 * 
 * @package NoOne4rever\Axessors\Tests
 */
class Color
{
    use Axessors;
    
    private $red, $green, $blue; #: +axs int `{$a = 0; $b = 255; return $var >= $a && $var <= $b;}`
    
    public function __construct(int $red, int $green, int $blue)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
    }
    
    public function mix(Color $color): Color
    {
        $red = $this->avg($this->red, $color->red);
        $green = $this->avg($this->green, $color->green);
        $blue = $this->avg($this->blue, $color->blue);
        
        return new Color($red, $green, $blue);
    }
    
    private function avg(int $a, int $b): float
    {
        return ($a + $b) / 2;
    }
}

AxessorsStartup::run();

$cyan = new Color(0, 255, 255);
$black = new Color(0, 0, 0);

$mixed = $cyan->mix($black);

echo <<<TXT
red: {$mixed->getRed()};
green: {$mixed->getGreen()};
blue: {$mixed->getBlue()};
TXT;
