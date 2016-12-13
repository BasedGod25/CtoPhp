<?php
$data = $_POST['data'];
$sem = $_POST['sem'];
$fp = fopen('tt' . $sem . '.xml', 'w');
fwrite($fp, $data);
fclose($fp);

$f1 = 'tt'. $sem . '.xml';
$f2 = './scheds/' . $_GET['nams'] . $sem . '.xml';
copy($f1, $f2);

header('Location: sched.php?semestr=' . $sem . '&nams=' . $_GET['nams']);

?>