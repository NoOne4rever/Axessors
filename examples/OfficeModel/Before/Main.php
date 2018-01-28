<?php

namespace NoOne4rever\Axessors\Examples\OfficeModel\Before;

require __DIR__ . '/IdCard.php';
require __DIR__ . '/Man.php';
require __DIR__ . '/Employee.php';
require __DIR__ . '/Room.php';
require __DIR__ . '/Unit.php';
require __DIR__ . '/Departament.php';
require __DIR__ . '/PastPosition.php';
require __DIR__ . '/Menu.php';

$card = new IdCard(792);
$card->setExpirationDate(time() + (60 * 60 * 24 * 2));

$directorCard = new IdCard(1);
$directorCard->setExpirationDate(PHP_INT_MAX >> 32);

$mainRoom = new Room(101);
$officeRoom = new Room(404);

$director = new Employee('No', 'One', 'Director');
$director->setIdCard($directorCard);

$employee = new Employee('Some', 'One', 'System Engineer');
$employee->setIdCard($card);
$employee->addRoom($mainRoom);
$employee->addRoom($officeRoom);

$programmersDepartament = new Departament('Programmers');
$programmersDepartament->addEmployee($employee);

$recruitersDepartament = new Departament('Recruiters');
$programmersDepartament->deleteEmployee($employee);
$employee->setPosition('HR');
$recruitersDepartament->addEmployee($employee);

echo <<<TXT
{$employee->getName()} {$employee->getSurname()}, {$employee->getPosition()}.
IdCard expires {$employee->getIdCard()->getExpirationDate()}.\n
TXT;

echo 'May be in the rooms:';
foreach ($employee->getRooms() as $room) {
    echo sprintf("%s + %d", PHP_EOL, $room->getNumber());
}
echo '.' . PHP_EOL;

echo <<<TXT
Belongs to departament "{$employee->getDepartament()->getName()}".
This departament has {$employee->getDepartament()->getPersonCount()} employee(s).\n
TXT;

echo 'Past positions:';
foreach ($employee->getPastPositions() as $position) {
    echo sprintf("%s + %s | %s", PHP_EOL, $position->getDepartament()->getName(), $position->getName());
}
echo '.' . PHP_EOL;

$menu = new Menu();
$employees = [$employee, $director];
$menu->showEmployees($employees);

echo "Director's card expires {$director->getIdCard()->getExpirationDate()}.";