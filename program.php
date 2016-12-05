<?php 
class Program{

    public $path = "";
    public $S;
    public $L;
    public $G;
    public $C;
    public $monthName = array("JANUARU", "FABRUARY", "MARCH", "APRIL", 
                                  "MAY", "JUNE", "JULY", "AUGUST", 
                                  "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");
    function __construct(){
        $this->S = new Schedule();
        $this->L = new Load(true);
        $this->G = new Graph();
        $this->C = new Config();
    }

    function toEng($s)
        {
            $ret = "";
            for ($i = 0; $i < strlen($s); $i++)
            {
                switch ($s[$i])
                {
                    case 'А': $ret .= "A"; break;
                    case 'Б': $ret .= "B"; break;
                    case 'В': $ret .= "V"; break;
                    case 'Г': $ret .= "G"; break;
                    case 'Д': $ret .= "D"; break;
                    case 'Е': $ret .= "E"; break;
                    case 'Ж': $ret .= "ZH"; break;
                    case 'З': $ret .= "Z"; break;
                    case 'И': $ret .= "I"; break;
                    case 'К': $ret .= "K"; break;
                    case 'Л': $ret .= "L"; break;
                    case 'М': $ret .= "M"; break;
                    case 'Н': $ret .= "N"; break;
                    case 'О': $ret .= "O"; break;
                    case 'П': $ret .= "P"; break;
                    case 'Р': $ret .= "R"; break;
                    case 'С': $ret .= "S"; break;
                    case 'Т': $ret .= "T"; break;
                    case 'У': $ret .= "U"; break;
                    case 'Ф': $ret .= "F"; break;
                    case 'Х': $ret .= "H"; break;
                    case 'Ц': $ret .= "C"; break;
                    case 'Ч': $ret .= "CH"; break;
                    case 'Ш': $ret .= "SH"; break;
                    case 'Щ': $ret .= "SCH"; break;
                    case 'Ь': $ret .= ""; break;
                    case 'Ы': $ret .= "Y"; break;
                    case 'Ъ': $ret .= ""; break;
                    case 'Э': $ret .= "E"; break;
                    case 'Ю': $ret .= "YU"; break;
                    case 'Я': $ret .= "YA"; break;

                    case 'а': $ret .= "a"; break;
                    case 'б': $ret .= "b"; break;
                    case 'в': $ret .= "v"; break;
                    case 'г': $ret .= "g"; break;
                    case 'д': $ret .= "d"; break;
                    case 'е': $ret .= "e"; break;
                    case 'ж': $ret .= "zh"; break;
                    case 'з': $ret .= "z"; break;
                    case 'и': $ret .= "i"; break;
                    case 'к': $ret .= "k"; break;
                    case 'л': $ret .= "l"; break;
                    case 'м': $ret .= "m"; break;
                    case 'н': $ret .= "n"; break;
                    case 'о': $ret .= "o"; break;
                    case 'п': $ret .= "p"; break;
                    case 'р': $ret .= "r"; break;
                    case 'с': $ret .= "s"; break;
                    case 'т': $ret .= "t"; break;
                    case 'у': $ret .= "u"; break;
                    case 'ф': $ret .= "f"; break;
                    case 'х': $ret .= "h"; break;
                    case 'ц': $ret .= "c"; break;
                    case 'ч': $ret .= "ch"; break;
                    case 'ш': $ret .= "sh"; break;
                    case 'щ': $ret .= "sch"; break;
                    case 'ь': $ret .= ""; break;
                    case 'ы': $ret .= "y"; break;
                    case 'ъ': $ret .= ""; break;
                    case 'э': $ret .= "e"; break;
                    case 'ю': $ret .= "yu"; break;
                    case 'я': $ret .= "ya"; break;
                    default: $ret .= $s[$i]; break;
                }
            }
            return $ret;
        }

        function getInJournal($temp)
        {
            if ($temp == 8) return 3;
            if ($temp == 10) return 4;
            if ($temp == 12) return 5;
            if ($temp == 17) return 6;
            if ($temp == 18) return 7;
            if ($temp == 19) return 8;
            if ($temp == 20) return 9;
            if ($temp == 21) return 10;
            if ($temp == 22) return 11;
            if ($temp == 23) return 12;
            if ($temp == 25) return 13;
            if ($temp == 26) return 14;
            if ($temp == 27) return 15;
            if ($temp == 28) return 16;
            if ($temp == 30) return 17;//
           // if ($temp == 30) return 18;
            return 1;
        }

        function Fill()
        {
            $chisl_flag = 0;
            if ($this->C->getNum())  $chisl_flag = 1;

            $this->G->buildGraph(
                $this->S, $this->L, $this->C->getStart(), $this->C->getEnd(), $chisl_flag,
                $this->C->getHolidays(),
                $this->C->getWorkTime(),
                $this->C->getChanges(),
                $this->C->getChangesCount(),
                $this->C->getStart4(),
                $this->C->getEnd4());

            $M = new MaxFlow($this->G);
            $this->V = $M->minCostMaxFlow();

            //&&&&&&&&&&&&&&????????????????????
            $misValue = null;

            $lines = "";

            $file = fopen($this->path.'out.txt', 'w');
            $D = array();


            for ($i = $this->G->count_of_edges - 1; $i >= 1; $i -= 2)
            {
                //$arr = array();
                //$key = null;
                //$D[] = (null => $arr);
                $E = $this->G->edges[$i];

                if ($E->x == $this->G->source || $E->y == $this->G->dest || $E->y == $this->G->source || $E->x == $this->G->dest)
                { continue; }
                if ($E->cap > 0)
                {
                    $spec = $this->L->spect[$this->G->x_id[$E->y - 500]];
                    $name = $this->L->names[$this->G->x_id[$E->y - 500]];
                    $ind = $this->getInJournal($this->G->y_id[$E->y - 500]);
                    $new_str = $spec.";".$name.";";
                    $date_g = new DateTime();
                    $date_g->setTimeStamp($this->G->dates[$E->x - 1]);
                    //var_dump($date_g);
                    $key =  $date_g->format('d/m/Y')."_".$new_str;
                    //^v
                    //$key =  $this->G->dates[$E->x - 1]->format('d/m/Y')."_".$new_str;
                    

                    if (!array_key_exists($key, $D))
                    {   
                    	//!!!???
                        $tt = array(256);
                        for ($j = 0; $j < 256; $j++)
                            $tt[$j] = 0;

                        $D[$key] = $tt;
                        $D[$key][$ind] .= $E->cap;
                    }
                    else
                    {
                        $D[$key][$ind] .= $E->cap;
                    }
                }
            }

            $totas = array(256);
            $total_month = array(256);
            //$lines .= "<table>";
            $lastMonth = -1;
            foreach (array_keys($D) as $key)
            {
                //var_dump(array_keys($D));
                $at = $D[$key];
                $index = strpos($key, '_');// key.IndexOf('_');
                $dat = str_replace("/", "-", substr($key, 0, $index));
                var_dump($dat);
                $now = new DateTime();
                $now = $now->setTimestamp(strtotime($dat));
                var_dump($now);

                if ($lastMonth == -1)
                    $lastMonth = date('n', $now->getTimestamp);

                //double[] at = D[key];
				$ar = $D[$key];

                if (date('n', $now->getTimestamp) != $lastMonth && $lastMonth != -1)
                {
                    $lastMonth = date('n',$now);
                    $lines .= '<tr class=\"total\">';
                    $lines .= "<td>"."TOTAL IN ";
                    $lines .= "</td>";

                    $lines .= "<td>";
                    #!!!!!!!!!!!!!!
                    $lines .= $this->monthName[date('n',$now) - 2];
                    $lines .= "</td>";

                    $total_in_month = 0.0;
                    for ($j = 0; $j < 16; $j++)
                    {
                        $total_in_month .= $total_month[$j + 3];
                        if ($total_month[$j + 3] != 0)
                            $lines .= "<td>".$total_month[$j + 3]."</td>";
                        else
                            $lines .= "<td></td>";
                        $total_month[$j + 3] = 0;
                    }
                    $lines .= "<td>".$total_in_month."</td>";
                }

                $lines .= "<tr>";
                $lines .= "<td>";
                $lines .= substr($key, 0, $index);
                $lines .= "</td>";

                $lines .= "<td>";
                $lines .= substr($key, $index + 1, strlen($key) - $index - 1);
                $lines .= "</td>";

                $total = 0.0;
                for ($j = 0; $j < 16; $j++)
                {
                    $totas[$j + 3] .= $at[$j + 3];
                    $total_month[$j + 3] .= $at[$j + 3];
                    $total .= $at[$j + 3];

                    if ($at[$j + 3] != 0)
                        $lines .= "<td>".$at[$j + 3]."</td>";
                    else
                        $lines .= "<td></td>";
                }
                $lines .= "<td>".$total."</td>";
            }

            $lines .= '<tr class=\"total\">';
            $lines .= "<td>"."TOTAL IN ";
            $lines .= "</td>";

            $lines .= "<td>";
            $lines .= $this->monthName[$lastMonth - 1];
            $lines .= "</td>";

            $total_in_month1 = 0.0;
            for ($j = 0; $j < 16; $j++)
            {
                $total_in_month1 .= $total_month[$j + 3];
                if ($total_month[$j + 3] != 0)
                    $lines .= "<td>".$total_month[$j + 3]."</td>";
                else
                    $lines .= "<td></td>";
                $total_month[$j + 3] = 0;
            }
            $lines .= "<td>".$total_in_month1."</td>";

            $lines .= "<tr>";
            $totty = 0.0;
            $lines .= '<td></td><td align=\"right\">TOTAL:</td>';
            for ($i = 2; $i < 18; $i++)
            {
                $totty .= $totas[$i + 1];
                if($totas[$i + 1] != 0)
                    $lines .= "<td>".$totas[i + 1]."</td>"; else
                    $lines .= "<td></td>";
            }
            $lines .= "<td>".$totty."</td>";
            $lines .= "</tr>";
            $lines .= "</table>";
            //var_dump($lines);
            //$file = fopen($this->path.'out.txt', 'w');
            fwrite($file, $lines);
            fclose($file);
        }

        public function Main($args)
        {
            
            $LoadFile = "example.xml";
            $ScheduleFile = "ttAutumn.xml";
            if (count($args) > 0)
            {
                $LoadFile = $args[0]; $ScheduleFile = $args[1];
            }

            if ($ScheduleFile == "ttAutumn.xml") $this->C->setSemestr("Autumn");
            else
            {
                $this->C->setSemestr("Spring");
                $this->L->isAutumn = false;
            }

            //var_dump($this->L->isAutumn);

            $this->S->LoadFromXML($this->path.$ScheduleFile);
            $this->L->LoadFromXML($this->path.$LoadFile);

            //FillExcel();
            $this->Fill();
        }
}


//}

 ?>