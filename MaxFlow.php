 <?php

    class MaxFlow
    { 
        const inf = 1000000000;
        const MAX = 2048;
        public $p = array(self::MAX);
        public $flag = array(self::MAX);
        public $D = array(self::MAX);
        public $dist = array(self::MAX);   
        public $G;

        function __construct($DG)
        {
            $this->G = new Graph();
            $this->G = $DG;
        }

        function BellmanFord($s, $d)
        {
            for ($i = 0; $i < self::MAX; $i++)
            {
                $this->D[$i] = self::inf;
            }
            $this->D[$s] = 0;

            for ($j = 0; $j < self::MAX; $j++)
                for ($i = 0; $i < $this->G->count_of_edges; $i++)
                    if ($this->D[$this->G->edges[$i]->y] > $this->D[$this->G->edges[$i]->x] + $this->G->edges[$i]->cost && $this->G->edges[$i]->cap > 0)
                        $this->D[$this->G->edges[$i]->y] = $this->D[$this->G->edges[$i]->x] + $this->G->edges[$i]->cost;
        }

        function existPath($s, $d, $k)
        {
            for ($i = 0; $i < self::MAX; $i++)
            {
                $this->flag[$i] = 0;
                $this->p[$i] = -1;
                $this->dist[$i] = self::inf;
            }

            $this->dist[$s] = 0;
            while (true)
            {
                $MIN = self::inf;
                $x = -1;
                for ($i = 0; $i < self::MAX; $i++)
                {
                    if ($MIN > $this->dist[$i] && $this->flag[$i] == 0)
                    {
                        $MIN = $this->dist[$i];
                        $x = $i;
                    }
                }
                if ($x == -1) break;
                $this->flag[$x] = 1;
                for ($i = 0; $i < $this->G->a[$x]->size(); $i++)
                {
                    $E = $this->G->edges[$this->G->a[$x]->a[$i]];
                    if ($this->dist[$E->y] > $this->dist[$E->x] + $E->cost + $this->D[$E->x] - $this->D[$E->y] && $E->cap > 0 && $this->flag[$E->y] == 0)
                    {
                        $this->dist[$E->y] = $this->dist[$E->x] + $E->cost + $this->D[$E->x] - $this->D[$E->y];
                        $this->p[$E->y] = $this->G->a[$x]->a[$i];
                    }
                }
            }

            for ($i = 0; $i < self::MAX; $i++)
            {
                $MX = self::inf;
                if ($MX > $this->D[$i] + $this->dist[$i]) $MX = $this->D[$i] + $this->dist[$i];
                $this->D[$i] = $MX;
            }
            return ($this->flag[$d] == 1);
        }

        private $q = array(self::MAX);
        private $qs = 0;
        private $qf = 0;
        
        function Aug($x, $y, $z)
        {
            while ($x != $y)
            {
                //echo "Flow: " . $this->G->edges[$this->p[$x]]->y . " " . $z . "<br>";
                $this->G->edges[$this->p[$x]]->cap -= $z;
                $this->G->edges[$this->G->edges[$this->p[$x]]->reverse]->cap += $z;
                $E = $this->G->edges[$this->p[$x]];
                $x = $E->x;
            }
            //echo $y . "<br>";
        }

        function findMin($x, $y)
        {
            $MIN = 1000000000;
            while ($x != $y)
            {
                $E = $this->G->edges[$this->p[$x]];
                if ($MIN > $E->cap) $MIN = $E->cap;
                $x = $E->x;
            }
            return $MIN;
        }

        function minCostMaxFlow()
        {
            $sum = 0;
            while ($this->existPath($this->G->source, $this->G->dest, 1))
            {
                $Min = $this->findMin($this->G->dest, $this->G->source);
                $sum += $Min;
                $this->Aug($this->G->dest, $this->G->source, $Min);
            }
            return $sum;
        }
    }
?>