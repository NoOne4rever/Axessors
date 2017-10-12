<?php

namespace Axessors\Types;

class axs_array_ext extends axs_array
{
	public static function m_in_deletePROPERTY($var, array $args): array
	{
		list($key) = $args;
		
		unset($var[$key]);
		return $var;
	}

	public static function m_in_addPROPERTY($var, array $args): array
	{
		$value = $args[0];
		$key   = $args[1] ?? null;

		if (is_null($key))
		{
			$var[] = $value;
		}
		else
		{
			$var[$key] = $value;
		}
		return $var;
	}

	public static function m_out_countPROPERTY($var): int
	{
		return count($var);
	}
}
