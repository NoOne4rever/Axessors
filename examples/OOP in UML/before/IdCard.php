<?php

/**
 * Class IdCard.
 * 
 * Employee's id card.
 */
class IdCard
{
    /** @var int card number */
	private $number;
	/** @var int timestamp of expiration date */
	private $dateExpire;

    /**
     * IdCard constructor.
     * 
     * @param int $number card number
     */
	public function __construct(int $number)
	{
		$this->number = $number;
	}

    /**
     * Getter for card number.
     * 
     * @see IdCard::$number
     * 
     * @return int card number
     */
	public function getNumber(): int
	{
		return $this->number;
	}

    /**
     * Getter for expiration date.
     * 
     * @see IdCard::$dateExpire
     * 
     * @return string date expire
     */
	public function getDateExpire(): string
	{
		return date('d.m.Y', $this->dateExpire);
	}

    /**
     * Setter for card number.
     * 
     * @see IdCard::$number
     * 
     * @param int $number new card number
     */
	public function setNumber(int $number): void
	{
		$this->number = $number;
	}

    /**
     * Setter for expiration date.
     * 
     * @see IdCard::$dateExpire
     * 
     * @param int $timestamp timestamp of the expiration date
     */
	public function setDateExpire(int $timestamp): void
	{
		$this->dateExpire = $timestamp;
	}
}