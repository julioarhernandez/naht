<?php
$year = 16;
$week_number = 51;
$days= $week_number*7;

echo date('M j, Y', strtotime("20".$year."-01-01 +".$days." days") );
?>


