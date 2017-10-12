<?php

namespace Axessors\Types;

abstract class Iterateable implements TypeDefinition
{
	final public static function is($var): bool
	{
		return is_iterable($var) && static::isThis($var);
	}

	abstract protected static function isThis($var): bool;
}
