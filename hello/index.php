<?php

include("vendor/autoload.php");

use Libs\Math\Circle;
use Support\Square;
use Carbon\Carbon;

$c = new Circle;
$s = new Square;

$c->area(123);
$s->area(234);

echo Carbon::now()->addDay(5);
