<?php
class Vertice{

	 const MAX_NUMBER_OF_NEIGHBOURS = 2048;
	 private $count = 0;
	 private $a = array(MAX_NUMBER_OF_NEIGHBOURS);

    function __construct(){
         $count = 0;
     }

    function size(){
         return count;
     }

    public function push_back($x){
        $a[$count++] = $x;
    }
}

class Edge{
    public $x,$y;
    public $cap;
    public $cost;
    public $reverse;

    public function __construct($x1,$y1,$cap1,$cost1,$reverse1){
        $x =$x1; $y = $y1;
        $cap = $cap1;
        $cost = $cost1;
        $reverse = $reverse1;
    }
}

class Graph {
    public $dest;
    public $source;

    public $MAX_NUMBER_OF_VERTICES = 2048;

    public $count_of_edges;
    public $x_id = array(2048);
    public $y_id = array(2048);

    public $edges;// = array($MAX_NUMBER_OF_VERTICES*$MAX_NUMBER_OF_VERTICES);
    public $D; //Dictionary<DateTime, int>

    public $countOfDates = 0;
    public $dates = array(2048);

    public $a = array(2048);

    public function __construct(){
        $count_of_edges = 0;
        $source = 0;
        $dest = 2000;
    }

    private function clear(){
        $count_of_edges = 0;
    }

    public function addEdge($x, $y, $cap, $cost){
        if ($a[$x] == null) $a[$x] = new Vertice();
        if ($a[$y] == null) $a[$y] = new Vertice();

        $a[$x].push_back($count_of_edges);
        $edges[$count_of_edges] = new Edge($x, $y, $cap, $cost, $count_of_edges + 1);
        $count_of_edges++;

        $a[$y].push_back($count_of_edges);
        $edges[$count_of_edges] = new Edge($y, $x, 0, -$cost, $count_of_edges - 1);
        $count_of_edges++;
   	}

	private function DayOfWeek2Int($d){

		if (date("w",$d)>=0 && date("w",$d) < 7){

				if (date("w",$d) == 0){
					return date("w",$d)+7;
				}
				else 
					return date("w",$d);
			}
			else return -1;
	}

	private function FindDay($date){
		if (null != array_search($date, $D)) {
			return $D[$date] + 1;
		}
			$D[$date] = $countOfDates;
			$dates[$countOfDates++] = $date;
			return $countOfDates;
	}

	public function date2int($date){
		if (null != array_search($date, $D)) {
			return $D[$date] + 1;
		}
		else return -1;	
	}


	public function addEdges($current_day, $i, $current_date, $L, $is_skip_for4){
		for ($j = 1; $j <= $L->countOfSubjects; $j++){

			if ($is_skip_for4 && $L->z[$j][1] == 4){
				continue;
			}

			for ($t = 25; $t <= 30; $t++){
				 //Перенос проведения любой нагрузки по дисциплине на любой день с худшим приоритетом
				addEdge($id[$j][$t], FindDay($current_date),$current_day->getHour($i),100);
			}

			addEdge($id[$j][8], FindDay($current_date),$current_day->getHour($i),100);
			addEdge($id[$j][10], FindDay($current_date),$current_day->getHour($i),100);
			addEdge($id[$j][12], FindDay($current_date),$current_day->getHour($i),100);
			addEdge($id[$j][17], FindDay($current_date),$current_day->getHour($i),100);
			addEdge($id[$j][22], FindDay($current_date),$current_day->getHour($i),100);

			//В этот день проводятся консультации
			if ($current_day->getType($i) == 19){
			//Проведение индивидуальной консультации по дисциплине
		        addEdge($id[$j][$current_day->getType(i)], FindDay($current_date), $current_day->getHour(i), 2);
		        //Рецензирование контрольной работы по дисциплине
		        addEdge($id[$j][22], FindDay($current_date), $current_day->getHour($i), 2);
		        //Перенос лекций, практик и лаб на дни консультаций
		        addEdge($id[$j][8], FindDay($current_date), $current_day->getHour($i), 3);
		        addEdge($id[$j][10], FindDay($current_date), $current_day->getHour($i), 3);
		        addEdge($id[$j][12], FindDay($current_date), $current_day->getHour($i), 3);

		        //Руководство уч. произв. и преддипл. практиками
		        addEdge($id[$j][25], FindDay($current_date), $current_day->getHour($i), 0);
		        //Руководство курсовой работы
		        addEdge($id[$j][26], FindDay($current_date), $current_day->getHour($i), 2);
		        //Руководство дипломной рабтой
		        addEdge($id[$j][27], FindDay($current_date), $current_day->getHour($i), 0);
		        //Руководство магистрами
		        addEdge($id[$j][28], FindDay($current_date), $current_day->getHour($i), 0);
		        //Руководство магистрами
		        addEdge($id[$j][29], FindDay($current_date), $current_day->getHour($i), 0);
		        //Руководство аспирантами и докторами
		        addEdge($id[$j][30], FindDay($current_date), $current_day->getHour($i), 0);		
		    }

	        if (!$current_day->getSpec($i)->Equals($L->spect[$j])) continue;
	                if (!$current_day->getDiscipline($i)->Equals($L->names[$j])) continue;
					//День в который проводятся пары по расписанию по данной дисциплинеи данной специальности и курсу
	                if ($current_day->getStarts($i) <= $current_date && $current_date <= $current_day->getEnds($i)){
	                    addEdge($id[$j][$current_day->getType($i)], FindDay($current_date), $current_day.getHour($i), 0);
	                }
	                //Индивидуальная консультация по дисциплине
	                addEdge($id[$j][17], FindDay($current_date), $current_day->getHour($i), 1);
	                //Рецензирование контрольной работы по дисциплине
	                addEdge($id[$j][22], FindDay($current_date), $current_day->getHour($i), 3);
	                //Руководство курсовой работой по дисциплине
	                addEdge($id[$j][26], FindDay($current_date), $current_day->getHour($i), 3);


		}
	}

	public function buildGraph($S, $L, $start_day, $last_day, $numerator, $holidays, $worktime, $changes, $changes_count, $start_day4, $end_day4){

		clear();
		$current_date = $last_day;
		$cnt = 0;

		for ($i = 1; $i <= $L->countOfSubjects; $i++){
			for ($j = 1; $j <= $L->countOfSubjects; $j++){
				$cnt++;
				$x_id[$cnt] = $i;
				$y_id[$cnt] = $j;
				$id[$i][$j]= $cnt +500;
				addEdge($source, $id[$i][$j], $L->z[$i][$j],0);
			}
		}

		$cnt_day = 0;

		while ($current_date <= $start_day) {
			$skip = false;
			$is_changed = false;

			$F = $current_date;

			$int_value =  DayOfWeek2Int($current_date); //!!!!

			for ($it = 0; $it < $changes_count; $it++){
				if ($changes[0][$it]->Equals($current_date)){
					$is_changed = true;
					$F = $changes[1][$it];
					break;
				}
			}

			if ($skip && !$is_changed){
			    if ($int_value == 7) $numerator = 1 - $numerator;
			    $current_date = $current_date -> sub(new DateInterval('P10d'));
			    $cnt_day++;
			    continue;
			}
		}	
	}
//Все функции addEdge1(FindDay($F),$dest,$worktime, 0) с 4 аргументами теперь 
	// public function addEdge1(FindDay($F),$dest,$worktime, 0){

	// 	if ($numerator == 1){
	// 		if ($numerator == 1){
	// 	        for ($i = 0; $i < $S->getNumeratorDay($int_value)->getCount(); $i++){
	// 	        	$current_date = new Day();
	// 	            $current_day = S -> getNumeratorDay($int_value);
	// 	            addEdges($current_day, $i, $F, $L, !($start_day4 <= $current_date && $current_date <= $end_day4));
	// 	        }
	// 	    }
	// 	    else
	// 	    {
	// 	        for ($i = 0; $i < $S -> getDenominatorDay($int_value)->getCount(); $i++){
	// 	        	$current_date = new Day();

	// 	            $current_day = $S->getDenominatorDay($int_value);
	// 	            addEdges($current_day, $i, $F, $L, !($start_day4 <= $current_date && $current_date <= $end_day4));
	// 	        }
	// 	    }
	// 		if ($int_value == 7) $numerator = 1 - $numerator;
	// 		$current_date = $current_date.AddDays(-1);
	// 		$cnt_day++;
	// 	}
	// }
}




?>
