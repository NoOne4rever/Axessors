<?php

class IdCard
{
	private $number;
	private $dateExpire;
	
	public function __construct(int $number)
	{
		$this->number = $number;
	}
	
	public function getNumber(): int
	{
		return $this->number;
	}
	
	public function getDateExpire(): string
	{
		return date('d.m.Y', $this->dateExpire);
	}
	
	public function setNumber(int $number): void
	{
		$this->number = $number;
	}
	
	public function setDateExpire(int $timestamp): void
	{
		$this->dateExpire = $timestamp;
	}
}