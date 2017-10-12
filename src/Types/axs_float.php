<?php
namespace Axessors\Types;

class axs_float implements TypeDefinition
{
	public static function is($var): bool
	{
		return is_int($var) || is_float($var);
	}
}
