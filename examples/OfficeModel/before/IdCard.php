<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\Before;

class IdCard
{
    private const EXPIRATION_TIME = 86400;
    private const DATE_FORMAT = 'd/m/Y';

    private $number;
    private $dateExpire;

    public function __construct(int $number)
    {
        $this->number = $number;
        $this->dateExpire = time() + self::EXPIRATION_TIME;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getExpirationDate(): string
    {
        return date(self::DATE_FORMAT, $this->dateExpire);
    }

    public function setExpirationDate(int $timestamp): void
    {
        $this->dateExpire = $timestamp;
    }
}