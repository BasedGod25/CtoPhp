<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include 'Config.php';
include 'Graph.php';
include 'Load.php';
include 'MaxFlow.php';
include 'Schedule.php';
include 'program.php';

$P = new Program();
$a = array('example.xml','ttAutumn.xml');
$P->main($a);
 ?>