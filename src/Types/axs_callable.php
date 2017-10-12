<?php

namespace Axessors\Types;

class axs_callable implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_callable($var);
	}
}
