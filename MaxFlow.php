 <?php

    class MaxFlow
    { 
        //include 'Graph.php'; - ошибка
        const inf = 1000000000;
        const MAX = 2048;
        public $p = array(MAX);
        public $flag = array(MAX);
        public $D = array(MAX);
        public $dist = array(MAX);

        
        public $G = Graph;

        function __construct($DG)
        {
            $G = new Graph();
            $G = $DG;
        }

        function BellmanFord($s, $d)
        {
            for ($i = 0; $i < $MAX; $i++)
            {
                $D[$i] = $inf;
            }
            $D[$s] = 0;

            for ($j = 0; $j < $MAX; $j++)
                for ($i = 0; $i < $G->count_of_edges; $i++)
                    if ($D[$G->edges[$i]->y] > $D[$G->edges[$i]->x] + $G->edges[$i]->cost && $G->edges[$i]->cap > 0)
                        $D[$G->edges[$i]->y] = $D[$G->edges[$i]->x] + $G->edges[$i]->cost;
        }

        function existPath($s, $d, $k)
        {
            for ($i = 0; $i < $MAX; $i++)
            {
                $flag[$i] = 0;
                $p[$i] = -1;
                $dist[$i] = $inf;
            }

            $dist[$s] = 0;
            while (true)
            {
                $MIN = $inf;
                $x = -1;
                for ($i = 0; $i < $MAX; $i++)
                {
                    if ($MIN > $dist[$i] && $flag[$i] == 0)
                    {
                        $MIN = $dist[$i];
                        $x = $i;
                    }
                }
                if ($x == -1) break;
                $flag[$x] = 1;
                for ($i = 0; $i < $G->a[$x].size(); $i++)
                {
                    $E = new Edge();
                    $E = $G->edges[$G->a[$x]->a[$i]];
                    if ($dist[$E->y] > $dist[$E->x] + $E->cost + $D[$E->x] - $D[$E->y] && $E->cap > 0 && $flag[$E->y] == 0)
                    {
                        $dist[$E->y] = $dist[$E->x] + $E->cost + $D[$E->x] - $D[$E->y];
                        $p[$E->y] = $G->a[$x]->a[$i];
                    }
                }
            }

            for ($i = 0; $i < $MAX; $i++)
            {
                $MX = $inf;
                if ($MX > $D[$i] + $dist[$i]) $MX = $D[$i] + $dist[$i];
                $D[$i] = $MX;
            }
            return ($flag[$d] == 1);
        }

        private $q = array(MAX);
        private $qs = 0;
        private $qf = 0;
        
        ///Перегрузка!!!
        function existPath($s, $d)
        {
            for ($i = 0; $i < $MAX; $i++)
            {
                $flag[$i] = 0;
                $p[$i] = 0;
            }
            $qs = $qf = 0;
            $q[$qf++] = $s;
            while ($qs != $qf)
            {
                $x = $q[$qs++];
                for ($i = 0; $i < $G->a[$x].size(); $i++)
                {
                    $E = new Edge();
                    $E = $G->edges[$G->a[$x]->a[$i]];
                    if ($E->cap <= 0) continue;
                    if ($flag[$E->y] == 0)
                    {
                        $flag[$E->y] = 1;
                        $p[$E->y] = $G->a[$x]->a[$i];
                        $q[$qf++] = $E->y;
                    }
                }
            }
            return ($flag[$d] == 1);
        }

        function Aug($x, $y, $z)
        {
            while ($x != $y)
            {
                $G->edges[$p[$x]]->cap -= $z;
                $G->edges[$G->edges[$p[$x]]->reverse]->cap += $z;
                $E = new Edge();
                $E = $G->edges[$p[$x]];
                $x = $E->x;
            }
        }

        function findMin($x, $y)
        {
            $MIN = 1000000000;
            while ($x != $y)
            {
                $E = new Edge();
                $E = $G->edges[$p[$x]];
                if ($MIN > $E->cap) $MIN = $E->cap;
                $x = $E->x;
            }
            return $MIN;
        }

        function minCostMaxFlow()
        {
            $sum = 0;
            while (existPath($G->source, $G->dest, 1))
            {
                $Min = findMin($G->dest, $G->source);
                $sum += $Min;
                Aug($G->dest, $G->source, $Min);
            }
            return $sum;
        }
    }
?>