<?php

namespace Axessors\Types;

use Axessors\Exceptions\TypeError;

class axs_int implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_int($var) || (is_float($var) && (int) $var == $var);
	}

	public static function m_in_incrementPROPERTY($var)
	{
		return ++$var;
	}

	public static function h_inc($var)
	{
		return ++$var;
	}
}
