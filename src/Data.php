<?php

namespace Axessors;

use Axessors\Exceptions\InternalError;

class Data
{
	private static $instance;

	private $classes = [];

	private function __construct() {}

	public function addClass(string $name, ClassData $class): void
	{
		$this->classes[$name] = $class;
	}

	public function getClass(string $name): ClassData
	{
		if (isset($this->classes[$name]))
		{
			return $this->classes[$name];
		}
		else
		{
			throw new InternalError("class \"$name\" not found in Axessors\\Data");
		}
	}

	public static function getInstance(): self
	{
		if (!isset(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
}
