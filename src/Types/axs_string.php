<?php

namespace Axessors\Types;

use Axessors\Exceptions\TypeError;

class axs_string implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_string($var);
	}

	public static function h_upper($var): string
	{
		return strtoupper($var);
	}

	public static function h_reverse($var): string
	{
		return strrev($var);
	}
}
