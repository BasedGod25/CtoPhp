<?php
class Vertice{

 const $MAX_NUMBER_OF_NEIGHBOURS = 2048;
 $count = 0;
 $a = array($MAX_NUMBER_OF_NEIGHBOURS);

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

    public __construct($x1,$y1,$cap1,$cost1,$reverse1){
        $x =$x1; $y = $y1;
        $cap = $cap1;
        $cost = $cost1;
        $reverse = $reverse1;
    }
}

class Graph(){
    public $dest;
    public $source;
    const $MAX_NUMBER_OF_VERTICES = 2048;
    public $count_of_edges;
    public $x_id = array($MAX_NUMBER_OF_VERTICES);
    public $y_id = array($MAX_NUMBER_OF_VERTICES);

    public $edges = array($MAX_NUMBER_OF_VERTICES * $MAX_NUMBER_OF_VERTICES);
    public $D; //Dictionary<DateTime, int>

    public $countOfDates = 0;
    public $dates = array($MAX_NUMBER_OF_VERTICES);

    public $a = array($MAX_NUMBER_OF_VERTICES);

    public __construct(){
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
}


?>
