<?php 
class Program{

	static $path = "";
    static $S = new Schedule();
    static $L = new Load(true);
    static $G = new Graph();
    static $C = new Config();
    static $monthName = array("JANUARU", "FABRUARY", "MARCH", "APRIL", 
                                  "MAY", "JUNE", "JULY", "AUGUST", 
                                  "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");

            static private string toEng(string s)
        {
            $ret = "";
            for ($i = 0; $i < strlen($s); $i++)
            {
                switch ($s[$i])
                {
                    case 'А': $ret += "A"; break;
                    case 'Б': $ret += "B"; break;
                    case 'В': $ret += "V"; break;
                    case 'Г': $ret += "G"; break;
                    case 'Д': $ret += "D"; break;
                    case 'Е': $ret += "E"; break;
                    case 'Ж': $ret += "ZH"; break;
                    case 'З': $ret += "Z"; break;
                    case 'И': $ret += "I"; break;
                    case 'К': $ret += "K"; break;
                    case 'Л': $ret += "L"; break;
                    case 'М': $ret += "M"; break;
                    case 'Н': $ret += "N"; break;
                    case 'О': $ret += "O"; break;
                    case 'П': $ret += "P"; break;
                    case 'Р': $ret += "R"; break;
                    case 'С': $ret += "S"; break;
                    case 'Т': $ret += "T"; break;
                    case 'У': $ret += "U"; break;
                    case 'Ф': $ret += "F"; break;
                    case 'Х': $ret += "H"; break;
                    case 'Ц': $ret += "C"; break;
                    case 'Ч': $ret += "CH"; break;
                    case 'Ш': $ret += "SH"; break;
                    case 'Щ': $ret += "SCH"; break;
                    case 'Ь': $ret += ""; break;
                    case 'Ы': $ret += "Y"; break;
                    case 'Ъ': $ret += ""; break;
                    case 'Э': $ret += "E"; break;
                    case 'Ю': $ret += "YU"; break;
                    case 'Я': $ret += "YA"; break;

                    case 'а': $ret += "a"; break;
                    case 'б': $ret += "b"; break;
                    case 'в': $ret += "v"; break;
                    case 'г': $ret += "g"; break;
                    case 'д': $ret += "d"; break;
                    case 'е': $ret += "e"; break;
                    case 'ж': $ret += "zh"; break;
                    case 'з': $ret += "z"; break;
                    case 'и': $ret += "i"; break;
                    case 'к': $ret += "k"; break;
                    case 'л': $ret += "l"; break;
                    case 'м': $ret += "m"; break;
                    case 'н': $ret += "n"; break;
                    case 'о': $ret += "o"; break;
                    case 'п': $ret += "p"; break;
                    case 'р': $ret += "r"; break;
                    case 'с': $ret += "s"; break;
                    case 'т': $ret += "t"; break;
                    case 'у': $ret += "u"; break;
                    case 'ф': $ret += "f"; break;
                    case 'х': $ret += "h"; break;
                    case 'ц': $ret += "c"; break;
                    case 'ч': $ret += "ch"; break;
                    case 'ш': $ret += "sh"; break;
                    case 'щ': $ret += "sch"; break;
                    case 'ь': $ret += ""; break;
                    case 'ы': $ret += "y"; break;
                    case 'ъ': $ret += ""; break;
                    case 'э': $ret += "e"; break;
                    case 'ю': $ret += "yu"; break;
                    case 'я': $ret += "ya"; break;
                    default: $ret += s[i]; break;
                }
            }
            return $ret;
        }

        static private getInJournal($temp)
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

        static private Fill()
        {
            $chisl_flag = 0;
            if ($C->getNum()) $chisl_flag = 1;

            $G->buildGraph($S, $L, $C->getStart(), $C->getEnd(), $chisl_flag,
                $C->getHolidays(),
                $C->getWorkTime(),
                $C->getChanges(),
                $C->getChangesCount(),
                $C->getStart4(),
                $C->getEnd4());

            $M = new MaxFlow($G);
            $V = $M->minCostMaxFlow();

//&&&&&&&&&&&&&&????????????????????
            $misValue = System.Reflection.Missing.Value;

            $lines = "";
           // System.IO.StreamWriter file = new System.IO.StreamWriter(path + "out.txt");

            $file = fopen($path.'out.txt', 'w');
            $D;
            //Dictionary<string, double[]> D = new Dictionary<string, double[]>();

            for ($i = $G->count_of_edges - 1; $i >= 1; $i -= 2)
            {
                $E = $G->edges[$i];
                if ($E->x == $G->source || $E->y == $G->dest || $E->y == $G->source || $E->x == $G->dest) continue;
                if ($E->cap > 0)
                {
                    $spec = $L->spect[$G->x_id[$E->y - 500]];
                    $name = $L->names[$G->x_id[$E->y - 500]];
                    $ind = getInJournal($G->y_id[$E->y - 500]);
                    $new_str = $spec + ";" + $name + ";";
                    $key = $G->dates[$E->x - 1].ToShortDateString() + "_" + $new_str;
                    if (!$D->ContainsKey($key))
                    {
                    	//!!!???
                        $tt = array(256);
                        for ($j = 0; $j < 256; $j++)
                            $tt[$j] = 0;

                        $D->Add($key, $tt);
                        $D[$key][$ind] += $E->cap;
                    }
                    else
                    {
                        $D[$key][$ind] += $E->cap;
                    }
                }
            }

            $totas = array(256);
            $total_month = array(256);
            //lines += "<table>";
            $lastMonth = -1;
            foreach ($D->Keys as $key)
            {
                $index = strpos($key, '_');// key.IndexOf('_');
                $dat = substr($key,0, $index);
                $now = strtotime(dat);
                if ($lastMonth == -1)
                    $lastMonth = date('n',$now();

                //double[] at = D[key];
				$ar = $D[$key];

                if (date('n',$now()) != $lastMonth && $lastMonth != -1)
                {
                    $lastMonth = date('n',$now());
                    $lines += '<tr class=\"total\">';
                    $lines += "<td>"."TOTAL IN ";
                    $lines += "</td>";

                    $lines += "<td>";
                    #!!!!!!!!!!!!!!
                    $lines += $monthName[date('n',$now()) - 2];
                    $lines += "</td>";

                    $total_in_month = 0.0;
                    for ($j = 0; $j < 16; $j++)
                    {
                        $total_in_month += $total_month[$j + 3];
                        if ($total_month[$j + 3] != 0)
                            $lines += "<td>".$total_month[$j + 3]."</td>";
                        else
                            $lines += "<td></td>";
                        $total_month[$j + 3] = 0;
                    }
                    $lines += "<td>".$total_in_month."</td>";
                }

                $lines += "<tr>";
                $lines += "<td>";
                $lines += substr($key,0, $index);
                $lines += "</td>";

                $lines += "<td>";
                $lines += substr($key,$index + 1, strlen($key) - $index - 1);
                $lines += "</td>";

                $total = 0.0;
                for ($j = 0; $j < 16; $j++)
                {
                    $totas[$j + 3] += $at[$j + 3];
                    $total_month[$j + 3] += $at[$j + 3];
                    $total += $at[$j + 3];

                    if ($at[$j + 3] != 0)
                        $lines += "<td>".$at[$j + 3]."</td>";
                    else
                        $lines += "<td></td>";
                }
                $lines += "<td>".$total."</td>";
            }

            $lines += '<tr class=\"total\">';
            $lines += "<td>"."TOTAL IN ";
            $lines += "</td>";

            $lines += "<td>";
            $lines += $monthName[$lastMonth - 1];
            $lines += "</td>";

            $total_in_month1 = 0.0;
            for ($j = 0; $j < 16; $j++)
            {
                $total_in_month1 += $total_month[$j + 3];
                if ($total_month[$j + 3] != 0)
                    $lines += "<td>".$total_month[$j + 3]$"</td>";
                else
                    $lines += "<td></td>";
                $total_month[$j + 3] = 0;
            }
            $lines += "<td>"$total_in_month1$"</td>";

            $lines += "<tr>";
            $totty = 0.0;
            $lines += '<td></td><td align=\"right\">TOTAL:</td>';
            for ($i = 2; $i < 18; $i++)
            {
                $totty += $totas[$i + 1];
                if($totas[$i + 1] != 0)
                    $lines += "<td>".$totas[i + 1]."</td>"; else
                    $lines += "<td></td>";
            }
            $lines += "<td>".$totty."</td>";
            $lines += "</tr>";
            $lines += "</table>";
            fwrite($file, $lines);
            fclose($file);
        }

        



}

 ?>