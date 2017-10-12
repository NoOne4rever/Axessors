<?php

namespace Axessors\Types;

class axs_bool implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_bool($var);
	}
}
