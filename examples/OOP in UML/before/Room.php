<?php

/**
 * Class Room.
 *
 * Employee's room.
 */
class Room
{
    /** @var int room number */
    private $number;

    /**
     * Room constructor.
     *
     * @param int $number room number
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * Getter for room number.
     *
     * @see Room::$number
     *
     * @return int room number
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Setter for room number.
     *
     * @see Room::$number
     *
     * @param int $number new room number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}