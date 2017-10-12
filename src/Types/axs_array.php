<?php

namespace Axessors\Types;

class axs_array extends Iterateable
{
	protected static function isThis($var): bool
	{
		return is_array($var);
	}

	public static function h_flip($var): array
	{
		return array_flip($var);
	}

	public static function h_shuffle($var): array
	{
		return shuffle($var);
	}
}
