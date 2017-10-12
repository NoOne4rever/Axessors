<?php

namespace Axessors\Types;

class axs_mixed implements TypeDefinition
{
	public static function is($var): bool
	{
		return true;
	}
}
