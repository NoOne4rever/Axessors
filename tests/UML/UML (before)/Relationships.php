<?php

const DIR = 'D:\PHP\PhpProjects\Axessors\Tests\UML (before)';

require_once DIR . '\Unit.php';
require_once DIR . '\Man.php';
require_once DIR . '\Employee.php';
require_once DIR . '\IdCard.php';
require_once DIR . '\Room.php';
require_once DIR . '\Departament.php';
require_once DIR . '\PastPosition.php';
require_once DIR . '\Menu.php';

$card = new IdCard(1);
$card->setDateExpire(time() + 86400);
$sysEngineer = new Employee('Paul', 'Francis', 'Engineer');
$sysEngineer->setIdCard($card);
$room1 = new Room(1);
$room2 = new Room(2);
$sysEngineer->setRoom($room1);
$sysEngineer->setRoom($room2);
$programmersDepartament = new Departament('Programmers');
$programmersDepartament->addEmployee($sysEngineer);
$sysEngineer->setPosition('Security');
$director = new Employee('Fyodor', 'Zhuchkov', 'Director');
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
echo "In departament \"{$sysEngineer->getDepartament()->getName()}\" works {$sysEngineer->getDepartament()->getPersonCount()} employee(s)." . PHP_EOL;