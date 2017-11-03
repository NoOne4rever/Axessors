<?php

const DIR = 'C:\Users\NoOne\Documents\UML (after)';

require 'C:\Users\NoOne\OneDrive\Documents\Axessors 2.0\Axessors.php';

require_once DIR . '\Unit.php';
require_once DIR . '\Man.php';
require_once DIR . '\Employee.php';
require_once DIR . '\IdCard.php';
require_once DIR . '\Room.php';
require_once DIR . '\Departament.php';
require_once DIR . '\PastPosition.php';
require_once DIR . '\Menu.php';

require 'C:\Users\NoOne\OneDrive\Documents\Axessors 2.0\Startup.php';

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