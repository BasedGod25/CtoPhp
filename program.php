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
	
	public $letters = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S");
								  
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
            if ($temp == 8)  return 3; //Лекция
            if ($temp == 10) return 4; //Практики
            if ($temp == 12) return 5; //Лаб. раб.
            if ($temp == 17) return 6; //Консультации по дисциплины
            if ($temp == 18) return 7; //Консультации перед экзаменом
            if ($temp == 19) return 8; //Индивидуальные консультации
            if ($temp == 20) return 9; //Зачеты
            if ($temp == 21) return 10; //Экзамены
            if ($temp == 22) return 11; //Контр. раб.
            if ($temp == 23) return 12; //ГЭК/ГАК
            if ($temp == 25) return 13; //Рук-во практиками
            if ($temp == 26) return 14; //Курсовая работа
            if ($temp == 27) return 15; //Рук-во дипломной работой
            if ($temp == 28) return 16; //Магистранты
            if ($temp == 30) return 17; //Аспиранты
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
            $misValue = null;

            $lines = "";
            $D = array();

			$outputFileName = './distribution.xlsx';
			$inputFileName = './Temp.xltx';

			//$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			$fileType = 'Excel2007';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
			$row = 1;
            for ($i = $this->G->count_of_edges - 1; $i >= 1; $i -= 2)
            {
                $E = $this->G->edges[$i];

                if ($E->x == $this->G->source || $E->y == $this->G->dest || $E->y == $this->G->source || $E->x == $this->G->dest) { 
                    continue; 
                }
                if ($E->cap > 0)
                {
                    $spec = $this->L->spect[$this->G->x_id[$E->y - 500]];
                    $name = $this->L->names[$this->G->x_id[$E->y - 500]];
                    $ind = $this->getInJournal($this->G->y_id[$E->y - 500]);
                    
                    /*echo $spec . "<br>";
                    echo $name . "<br>";
                    echo $this->G->y_id[$E->y - 500] . " " . $E->y . "<br>";*/
                    
                    $new_str = $spec . ";" . $name . ";";
                    $date_g = new DateTime();
                    $date_g->setTimeStamp($this->G->dates[$E->x - 1]);
                    $key = $date_g->format('d/m/Y')."_".$new_str;

                    if (!array_key_exists($key, $D))
                    {   
                        $tt = array(256);
                        for ($j = 0; $j < 256; $j++)
                            $tt[$j] = 0;
                        $D[$key] = $tt;
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

            $lines .= '<link rel="stylesheet" href="style.css">';
            $lines .= "<table class=\"example\">";
            $lines .="    <thead>
    <th class=\"vertical\" style=\"width: 50px;\">Дата</th>
    <th class=\"vertical\" style=\"width: 300px;\">Специальность</th>
    <th class=\"vertical\" style=\"width: 30px;\">Лекции</th>
    <th class=\"vertical\" style=\"width: 30px;\">Практики</th>
    <th class=\"vertical\" style=\"width: 30px;\">Лабораторные работы</th>
    <th class=\"vertical\" style=\"width: 30px;\">Консультации по дисциплине</th>
    <th class=\"vertical\" style=\"width: 30px;\">Консультации перед экзаменом</th>
    <th class=\"vertical\" style=\"width: 30px;\">Индивидуальные консультации</th>
    <th class=\"vertical\" style=\"width: 30px;\">Зачет</th>
    <th class=\"vertical\" style=\"width: 30px;\">Экзамен</th>
    <th class=\"vertical\" style=\"width: 30px;\">Рецензирование контрольных работ</th>
    <th class=\"vertical\" style=\"width: 30px;\">ГАК и ГЭК</th>
    <th class=\"vertical\" style=\"width: 30px;\">Руководство практиками</th>
    <th class=\"vertical\" style=\"width: 30px;\">Руководство курсовыми работами</th>
    <th class=\"vertical\" style=\"width: 30px;\">Руководство дипломными работами</th>
    <th class=\"vertical\" style=\"width: 30px;\">Руководство аспирантами</th>
    <th class=\"vertical\" style=\"width: 30px;\">Руководство магистрами</th>
    <th class=\"vertical\" style=\"width: 30px;\">Руководство докторами</th>
    <th class=\"vertical\" style=\"width: 30px;\">Всего</th>
    </thead>";
            $lastMonth = -1;
			$subjects = array(256);
			$subjectsCount = 0;
			
            foreach (array_keys($D) as $key)
            {
                $at = $D[$key];
                $index = strpos($key, '_');
                $dat = str_replace("/", "-", substr($key, 0, $index));
                $now = new DateTime();
                $now = $now->setTimestamp(strtotime($dat));
                if ($lastMonth == -1)
                    $lastMonth = date('n', $now->getTimestamp);
                $ar = $D[$key];

                if (date('n', $now->getTimestamp) != $lastMonth && $lastMonth != -1)
                {
                    $lastMonth = date('n',$now);
                    $lines .= '<tr class="total">';
                    $lines .= "<td>"."TOTAL IN ";
                    $lines .= "</td>";

                    $lines .= "<td>";
                    $lines .= $this->monthName[date('n',$now) - 2];
                    $lines .= "</td>";

                    $total_in_month = 0.0;
                    for ($j = 0; $j < 16; $j++)
                    {
                        $total_in_month += $total_month[$j + 3];
                        if ($total_month[$j + 3] != 0)
                            $lines .= "<td>".$total_month[$j + 3]."</td>";
                        else
                            $lines .= "<td></td>";
                        $total_month[$j + 3] = 0;
                    }
                    $lines .= "<td>".$total_in_month."</td>";
                }

				$row++;
                $lines .= "<tr>";
                $lines .= "<td>";
                $lines .= substr($key, 0, $index);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, substr($key, 0, $index));
                $lines .= "</td>";

                $lines .= "<td>";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, substr($key, $index + 1, strlen($key) - $index - 1));
                $lines .= substr($key, $index + 1, strlen($key) - $index - 1);
                $lines .= "</td>";
				
				$flag = false;
				for($hl = 0; $hl < $subjectsCount; $hl++) {
					if(substr($key, $index + 1, strlen($key) - $index - 1) == $subjects[$hl]) {
						$flag = true; break;
					}
				}
				if(!$flag) {
					$subjects[$subjectsCount++] = substr($key, $index + 1, strlen($key) - $index - 1);
				}

                $total = 0.0;
                for ($j = 0; $j < 16; $j++)
                {
                    $totas[$j + 3] += $at[$j + 3];
                    $total_month[$j + 3] += $at[$j + 3];
                    $total += $at[$j + 3];
                    if ($at[$j + 3] != 0) {
                        $lines .= "<td>".$at[$j + 3]."</td>";
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j + 2, $row, $at[$j + 3]);
					}
                    else
                        $lines .= "<td></td>";
                }
                $lines .= "<td>".$total."</td>";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j + 2, $row, "=SUM(C" . $row. ":R" . $row . ")");
            }

			for($i = 0; $i < 17; $i++) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i + 2, $row + 1, "=SUM(" . $this->letters[$i] . 1 . ":" . $this->letters[$i] . $row . ")");
				$objPHPExcel->getActiveSheet()->getColumnDimension($this->letters[$i])->setWidth(5);
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
                $total_in_month1 += $total_month[$j + 3];
                if ($total_month[$j + 3] != 0)
                    $lines .= "<td>".$total_month[$j + 3]."</td>";
                else
                    $lines .= "<td></td>";
                $total_month[$j + 3] = 0;
            }
            $lines .= "<td>".$total_in_month1."</td>";

            $lines .= "<tr>";
            $totty = 0.0;
            $lines .= '<td></td><td align="right">TOTAL:</td>';
            for ($i = 2; $i < 18; $i++)
            {
                $totty += $totas[$i + 1];
                if($totas[$i + 1] != 0)
                    $lines .= "<td>".$totas[i + 1]."</td>"; else
                    $lines .= "<td></td>";
            }
            $lines .= "<td>".$totty."</td>";
            $lines .= "</tr>";
            $lines .= "</table>";
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(11);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
			$lastRow = $row;
			$row += 5;
			for($hl = 0; $hl < $subjectsCount; $hl++) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $subjects[$hl]);
				for($lt = 0; $lt < 17; $lt++) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($lt + 2, $row, "=SUMIF(B2:B" . $lastRow . ",\"" . $subjects[$hl] . "\"," . $this->letters[$lt] . "2:" . $this->letters[$lt] . $lastRow . ")");
				}
				$row++;
			}
			
			$objWriter->save($outputFileName);
            return $lines;
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

            $this->S->LoadFromXML($this->path.$ScheduleFile);
            $this->L->LoadFromXML($this->path.$LoadFile);

            return $this->Fill();
        }
}

?>