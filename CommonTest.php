<?php

use NoOne4rever\Axessors\{
    Axessors, AxessorsStartup, Exceptions\AxessorsError
};

require __DIR__ . '/Axessors.phar';

class Point
{
    use Axessors;

    private $x, $y; #: +axs int

    public function Point(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}

class Line
{
    use Axessors;

    private $start; #: +axs Point
    private $end; #: +axs Point => default

    public function Line(Point $start, Point $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
}

AxessorsStartup::run();

$start = new Point(10, 10);
$end = new Point(20, 20);
$line = new Line($start, $end);

$line->getStart()->setX(15);
echo $line->getStart()->getX(), PHP_EOL;

$line->get();
try {
    $line->getEnd();
} catch (AxessorsError $error) {
    echo $error->getMessage();
}