<?php

namespace Axessors\Types;

class axs_object implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_object($var);
	}
}
