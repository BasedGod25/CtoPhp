<?php 
class Config {
	private $holidays = array(256)	
	private $count = 0;
	private $changes = array(2,256);
	private $count_changes = 0;

	private $s1 = new DateTime(date("Y"), 9, 1);
	private $e1 = new DateTime(date("Y"), 12, 31);
	private $s4 = new DateTime(date("Y"), 9, 1);
	private $e4 = new DateTime(date("Y"), 12, 31);

	private $autumnSemestr = false;

	function addHoliday($dt){
		$holidays[$count=$count+1] = $dt;
	}

	function getChangesCount(){
		return $count_changes;
	}

	function getHolidays(){
		return $holidays;
	}

	function getChanges(){
		return $changes;
	}

	function updateDateTime(){
		$count = 0;
	}

	function setSemesterStart($c1s, $c1e, $c4s, $c4e){
		$s1 = $c1s;
		$e1 = $c1e;
		$s4 = $c4s;
		$e4 = $c4e;
	}
}
?>