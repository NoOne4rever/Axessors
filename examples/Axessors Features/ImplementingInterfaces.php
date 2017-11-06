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
use NoOne4rever\Axessors\Axs;

require 'C:/Users/NoOne/Documents/GitHub/Axessors/vendor/autoload.php';

/** Interface iSample. */
interface iSample
{
	# public function getField
}

/** Class IncrementSample. */
abstract class IncrementSample
{
	use Axs;

	# abstract protected function incrementField
	# abstract protected function setField
}

/** Sample class. */
class SampleClass extends IncrementSample implements iSample
{
	use Axessors;

	/** @var string a field */
	private $field = 'value'; #> ~wrt string|int +rdb
}

require 'C:/Users/NoOne/Documents/GitHub/Axessors/src/Startup.php';

echo PHP_EOL;