<?php

const EXAMPLES_HOME = 'C:/Users/NoOne/Documents/GitHub/Axessors/examples/OOP in UML/after';
const AXESSORS_HOME = 'C:/Users/NoOne/Documents/GitHub/Axessors/src';

require AXESSORS_HOME . '/Axessors.php';

require_once EXAMPLES_HOME . '/Unit.php';
require_once EXAMPLES_HOME . '/Man.php';
require_once EXAMPLES_HOME . '/Employee.php';
require_once EXAMPLES_HOME . '/IdCard.php';
require_once EXAMPLES_HOME . '/Room.php';
require_once EXAMPLES_HOME . '/Departament.php';
require_once EXAMPLES_HOME . '/PastPosition.php';
require_once EXAMPLES_HOME . '/Menu.php';

require AXESSORS_HOME . '/Startup.php';

$programmersDepartament = new Departament('Programmers');
$card = new IdCard(1);
$card->setDateExpire(time() + 86400);
$sysEngineer = new Employee('Paul', 'Francis', 'Engineer', $programmersDepartament);
$sysEngineer->setIdCard($card);
$room1 = new Room(1);
$room2 = new Room(2);
$sysEngineer->addRoom($room1);
$sysEngineer->addRoom($room2);
$programmersDepartament->addEmployees($sysEngineer);
$sysEngineer->setPosition('Security');
$director = new Employee('Fyodor', 'Zhuchkov', 'Director', $programmersDepartament);
$employees = [
    $sysEngineer,
    $director
];

echo "{$sysEngineer->getName()} works as {$sysEngineer->getPosition()}." . PHP_EOL;
echo "Id card expires {$card->getDateExpire()}." . PHP_EOL;
echo "He may be in cabinets:";
foreach ($sysEngineer->getRoom() as $room) {
    echo PHP_EOL . "{$room->getNumber()}";
}
echo '.' . PHP_EOL;
echo "Belongs to {$sysEngineer->getDepartament()->getName()}." . PHP_EOL;
echo "Had worked as:";
foreach ($sysEngineer->getPastPosition() as $position) {
    echo PHP_EOL . "{$position->getName()} in departament \"{$position->getDepartament()->getName()}\"";
}
echo '.' . PHP_EOL;
Menu::showEmployees($employees);
echo "In departament \"{$sysEngineer->getDepartament()->getName()}\" works {$sysEngineer->getDepartament()->countEmployees()} employee(s)." . PHP_EOL;