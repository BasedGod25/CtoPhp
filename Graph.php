<?php
class Vertice {

	public $MAX_NUMBER_OF_NEIGHBOURS = 2048;
	public $count;
	public $a;

	function __construct(){
         $this->count = 0;
         $this->a = array($this->MAX_NUMBER_OF_NEIGHBOURS);
    }

        public function size(){
        return $this->count;
    }

    public function push_back($x){
        $this->a[$this->count++] = $x;
    }
}

class Edge{
    public $x,$y;
    public $cap;
    public $cost;
    public $reverse;

    public function __construct($x1,$y1,$cap1,$cost1,$reverse1){
        $this->x = $x1; $this->y = $y1;
        $this->cap = $cap1;
        $this->cost = $cost1;
        $this->reverse = $reverse1;
    }
}

class Graph {
    public $dest;
    public $source;

    public $MAX_NUMBER_OF_VERTICES = 2048;
    public $count_of_edges;
    public $id = array(array(64),array(64));
    public $x_id = array(2048);
    public $y_id = array(2048);
    public $edges;
    public $D;

    public $countOfDates = 0;
    public $dates = array(2048);

    public $a = array(2048);

    public function __construct() {
        $this->count_of_edges = 0;
        $this->source = 0;
        $this->dest = 2000;
        $this->D = array();
        for ($i=0; $i < 2048; $i++)
        {
        	$this->a[$i] = new Vertice();
        }
    }

    private function clear() {
        $this->count_of_edges = 0;
    }

    public function addEdge($x, $y, $cap, $cost) {
        if ($this->a[$x] == null) $this->a[$x] = new Vertice();
        if ($this->a[$y] == null) $this->a[$y] = new Vertice();
        if ($cap == null) $cap = 0;

        $this->a[$x]->push_back($this->count_of_edges);
        $this->edges[$this->count_of_edges] = new Edge($x, $y, $cap, $cost, $this->count_of_edges + 1);
        $this->count_of_edges++;

        $this->a[$y]->push_back($this->count_of_edges);
        $this->edges[$this->count_of_edges] = new Edge($y, $x, 0, -$cost, $this->count_of_edges - 1);
        $this->count_of_edges++;
    }

    private function DayOfWeek2Int($d) {
        $d = date_timestamp_get($d);
        if (date("w", $d)>=0 && date("w",$d) < 7) {
            if (date("w", $d) == 7){
                return 0;
            }
            else 
                return date("w",$d)+1;
        }
        else return -1;
    }

    private function FindDay($date1) {
        $date = $date1->getTimestamp();
        for($i = 0; $i < $this->countOfDates; $i++) {
            if($date === $this->dates[$i]) {
                return $i + 1;
            }
        }

        $this->dates[$this->countOfDates++] = $date;
        return $this->countOfDates;
    }

    public function date2int($date) {
        if (null != array_search($date, $this->D)) {
            return $this->D[$date] + 1;
        }
        else return -1;	
    }

    public function addEdges($current_day, $i, $current_date, $L, $is_skip_for4) {
        for ($j = 1; $j <= $L->countOfSubjects; $j++) {
            if ($is_skip_for4 && $L->z[$j][1] == 4) {
                continue;
            }

            for ($t = 25; $t <= 30; $t++){
                $this->addEdge($this->id[$j][$t], $this->FindDay($current_date), $current_day->getHour($i),100);
            }

            $this->addEdge($this->id[$j][8], $this->FindDay($current_date),$current_day->getHour($i),100);
            $this->addEdge($this->id[$j][10], $this->FindDay($current_date),$current_day->getHour($i),100);
            $this->addEdge($this->id[$j][12], $this->FindDay($current_date),$current_day->getHour($i),100);
            $this->addEdge($this->id[$j][17], $this->FindDay($current_date),$current_day->getHour($i),100);
            $this->addEdge($this->id[$j][22], $this->FindDay($current_date),$current_day->getHour($i),100);

            //В этот день проводятся консультации
            if ($current_day->getType($i) == 19) {
                //Проведение индивидуальной консультации по дисциплине
                $this->addEdge($this->id[$j][$current_day->getType($i)], FindDay($current_date), $current_day->getHour($i), 2);
                //Рецензирование контрольной работы по дисциплине
                $this->addEdge($this->id[$j][22], FindDay($current_date), $current_day->getHour($i), 2);
                //Перенос лекций, практик и лаб на дни консультаций
                $this->addEdge($this->id[$j][8], FindDay($current_date), $current_day->getHour($i), 3);
                $this->addEdge($this->id[$j][10], FindDay($current_date), $current_day->getHour($i), 3);
                $this->addEdge($this->id[$j][12], FindDay($current_date), $current_day->getHour($i), 3);

                //Руководство уч. произв. и преддипл. практиками
                $this->addEdge($this->id[$j][25], $this->FindDay($current_date), $current_day->getHour($i), 0);
                //Руководство курсовой работы
                $this->addEdge($this->id[$j][26], $this->FindDay($current_date), $current_day->getHour($i), 2);
                //Руководство дипломной рабтой
                $this->addEdge($this->id[$j][27], $this->FindDay($current_date), $current_day->getHour($i), 0);
                //Руководство магистрами
                $this->addEdge($this->id[$j][28], $this->FindDay($current_date), $current_day->getHour($i), 0);
                //Руководство магистрами
                $this->addEdge($this->id[$j][29], $this->FindDay($current_date), $current_day->getHour($i), 0);
                //Руководство аспирантами и докторами
                $this->addEdge($this->id[$j][30], $this->FindDay($current_date), $current_day->getHour($i), 0);
            }
        
            if (!($current_day->getSpec($i)==$L->spect[$j])) continue;
            if (!($current_day->getDiscipline($i)==$L->names[$j])) continue;
            //День в который проводятся пары по расписанию по данной дисциплинеи данной специальности и курсу
            if ($current_day->getStarts($i) <= $current_date && $current_date <= $current_day->getEnds($i)){
                $this->addEdge($this->id[$j][$current_day->getType($i)], $this->FindDay($current_date), $current_day->getHour($i), 0);
            }
            //Индивидуальная консультация по дисциплине
            $this->addEdge($this->id[$j][17], $this->FindDay($current_date), $current_day->getHour($i), 1);
            //Рецензирование контрольной работы по дисциплине
            $this->addEdge($this->id[$j][22], $this->FindDay($current_date), $current_day->getHour($i), 3);
            //Руководство курсовой работой по дисциплине
            $this->addEdge($this->id[$j][26], $this->FindDay($current_date), $current_day->getHour($i), 3);
        }
    }

	public function buildGraph($S, $L, $start_day, $last_day, $numerator, $holidays, $worktime, $changes, $changes_count, $start_day4, $end_day4){

		$this->clear();
		$current_date = $last_day;
		$cnt = 0;

		for ($i = 1; $i <= $L->countOfSubjects; $i++){
                    for ($j = 1; $j <= $L->countOfTypes; $j++){
                        $cnt++;
                        $this->x_id[$cnt] = $i;
                        $this->y_id[$cnt] = $j;
                        $this->id[$i][$j]= $cnt + 500;

                        $this->addEdge($this->source, $this->id[$i][$j], $L->z[$i][$j], 0);
                    }
		}

		$cnt_day = 0;

		while ($current_date >= $start_day) 
		{
			$cnt_day++;
			$skip = false;
			$is_changed = false;

			$F = $current_date;

			$int_value =  $this->DayOfWeek2Int($current_date); //!!!!

			for ($it = 0; $it < $changes_count; $it++){
				if ($changes[0][$it]==($current_date)){
					$is_changed = true;
					$F = $changes[1][$it];
					break;
				}
			}

			for ($it = 0; $it < count($holidays); $it++){
				if ($holidays[$it]==($current_date)){
					$skip = true;
					break;
				}
			}

            if ($skip && !$is_changed) {
                $timestamp = date_timestamp_get($current_date);
                if ($int_value == 7) $numerator = 1 - $numerator;
                $current_date->setTimestamp(strtotime('-1 day', $timestamp));
                $cnt_day++;
                continue;
            }

            $this->addEdge($this->FindDay($F), $this->dest, $worktime, 0);
            if ($numerator == 1)
            {
                for ($i = 0; $i < $S->getNumeratorDay($int_value)->getCount(); $i++)
                {
                    $current_day = $S->getNumeratorDay($int_value);
                    $this->addEdges($current_day, $i, $F, $L, !($start_day4 <= $current_date && $current_date <= $end_day4));
                }
            }
            else
            {
                for ($i = 0; $i < $S->getDenominatorDay($int_value)->getCount(); $i++)
                {
                    $current_day = $S->getDenominatorDay($int_value);
                    $this->addEdges($current_day, $i, $F, $L, !($start_day4 <= $current_date && $current_date <= $end_day4));
                }
            }
            if ($int_value == 7) $numerator = 1 - $numerator;
            $timestamp = date_timestamp_get($current_date);
            $current_date->setTimestamp(strtotime('-1 day', $timestamp));
            $cnt_day++;
        }	
    }
}
?>
