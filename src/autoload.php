<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors;

use NoOne4rever\Axessors\Exceptions\InternalError;

spl_autoload_register(function(string $class)
{
	static $required = [];
	$possibleDirs = ['.', 'Types', 'Exceptions'];
	foreach ($possibleDirs as $dir)
	{
		$file = __DIR__ . "\\$dir\\" . basename($class) . '.php';
		if (file_exists($file) && !in_array(basename($class), $required))
		{
			require $file;
			$required[] = basename($class);
			return;
		}
	}
	throw new InternalError("class \"$class\" not found while executing autoload");
});
