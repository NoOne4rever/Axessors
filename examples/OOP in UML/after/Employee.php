<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @package NoOne4rever\Axessors
 * @license GPL
 */

namespace NoOne4rever\Axessors\Examples;

use NoOne4rever\Axessors\Axessors;

/**
 * Class Employee.
 *
 * Stores information about employee.
 *
 * @method void deleteRoom(Room $room) deletes employee's room
 * @method void deletePastPosition(PastPosition $pastPosition) deletes past position
 * @method string getPosition() getter for position
 * @method IdCard getIdCard() getter for id card
 * @method Room[] getRoom() getter for rooms
 * @method Departament getDepartament() getter for departament
 * @method PastPosition[] getPastPosition() getter for past positions
 * @method void setPosition(string $position) setter for position
 * @method void setIdCard(IdCard $idCard) setter for id card
 * @method void addRoom(Room $room) setter for room
 * @method void setPastPosition(PastPosition $position) setter for past position
 */
class Employee extends Man
{
    use Axessors;

    /** @var string employee's position */
    private $position; #> +axs string >> `$this->addPastPosition(new :PastPosition($this->position, $this->departament))`
    /** @var IdCard employee's id card */
    private $idCard; #> +axs IdCard
    /** @var Room[] rooms */
    private $room = []; #> +axs Array[Room]
    /** @var Departament employee's departament */
    private $departament; #> +axs Departament
    /** @var PastPosition[] past positions */
    private $pastPosition = []; #> +axs Array[PastPosition]

    /**
     * Employee constructor.
     *
     * @param string $name name
     * @param string $surname second name
     * @param string $position employee's position
     * @param Departament $departament departament
     */
    public function __construct(string $name, string $surname, string $position, Departament $departament)
    {
        parent::__construct($name, $surname);
        $this->position = $position;
        $this->departament = $departament;
    }
}