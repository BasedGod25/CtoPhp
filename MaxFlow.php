 <?php

    class MaxFlow
    { 
        //include 'Graph.php'; - ошибка
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
                $D[$i] = self::inf;
            }
            $D[$s] = 0;

            for ($j = 0; $j < self::MAX; $j++)
                for ($i = 0; $i < $G->count_of_edges; $i++)
                    if ($D[$G->edges[$i]->y] > $D[$G->edges[$i]->x] + $G->edges[$i]->cost && $G->edges[$i]->cap > 0)
                        $D[$G->edges[$i]->y] = $D[$G->edges[$i]->x] + $G->edges[$i]->cost;
        }

        function existPath($s, $d, $k)
        {
            for ($i = 0; $i < self::MAX; $i++)
            {
                $flag[$i] = 0;
                $p[$i] = -1;
                $dist[$i] = self::inf;
            }

            $dist[$s] = 0;
            while (true)
            {
                $MIN = self::inf;
                $x = -1;
                for ($i = 0; $i < self::MAX; $i++)
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

            for ($i = 0; $i < self::MAX; $i++)
            {
                $MX = self::inf;
                if ($MX > $D[$i] + $dist[$i]) $MX = $D[$i] + $dist[$i];
                $D[$i] = $MX;
            }
            return ($flag[$d] == 1);
        }

        private $q = array(self::MAX);
        private $qs = 0;
        private $qf = 0;
        
        ///Перегрузка!!!
        // function existPath($s, $d)
        // {
        //     for ($i = 0; $i < self::MAX; $i++)
        //     {
        //         $flag[$i] = 0;
        //         $p[$i] = 0;
        //     }
        //     $qs = $qf = 0;
        //     $q[$qf++] = $s;
        //     while ($qs != $qf)
        //     {
        //         $x = $q[$qs++];
        //         for ($i = 0; $i < $G->a[$x].size(); $i++)
        //         {
        //             $E = new Edge();
        //             $E = $G->edges[$G->a[$x]->a[$i]];
        //             if ($E->cap <= 0) continue;
        //             if ($flag[$E->y] == 0)
        //             {
        //                 $flag[$E->y] = 1;
        //                 $p[$E->y] = $G->a[$x]->a[$i];
        //                 $q[$qf++] = $E->y;
        //             }
        //         }
        //     }
        //     return ($flag[$d] == 1);
        // }

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
            while ($this->existPath($G->source, $G->dest, 1))
            {
                $Min = findMin($G->dest, $G->source);
                $sum += $Min;
                Aug($G->dest, $G->source, $Min);
            }
            return $sum;
        }
    }
?>