<?php

namespace Axessors\Types;

class axs_resource implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_resource($var);
	}
}
