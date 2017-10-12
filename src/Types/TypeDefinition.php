<?php

namespace Axessors\Types;

interface TypeDefinition
{
	public static function is($var): bool;
}
