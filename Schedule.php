<?php
    class Day
    {
        public $countOfDisciplines = 0;
        public $hour = array(32);
        public $spec = array(32);
        public $disc = array(32);
        public $kurs = array(32);
        public $typs = array(32);
        public $pair = array(32);

        public $starts = array(32);
        public $ends = array(32);

        function __construct()
        {
            $countOfDisciplines = 0;
        }
        
        public function addDisc($hours, $speciality, $typ, $name, $kurs1, $when, $strt, $end)
        {

            if (strrpos($name, ":"))
            {
                $name = substr($name, strrpos($name, ":"));
            }
            $hour[$countOfDisciplines] = $hours;
            $typs[$countOfDisciplines] = $typ;
            $disc[$countOfDisciplines] = $name;
            $kurs[$countOfDisciplines] = $kurs1;
            $pair[$countOfDisciplines] = $when;
            $starts[$countOfDisciplines] = $strt;
            $ends[$countOfDisciplines] = $end;
            $spec[$countOfDisciplines++] = $speciality;
            $countOfDisciplines++;
        }

        function Remove($index)
        {
            if ($index < 0) return;
            for ($i = $index; $i < $countOfDisciplines - 1; $i++)
            {
                $hour[$i] = $hour[$i + 1];
                $typs[$i] = $typs[$i + 1];
                $disc[$i] = $disc[$i + 1];
                $kurs[$i] = $kurs[$i + 1];
                $pair[$i] = $pair[$i + 1];
                $spec[$i] = $spec[$i + 1];

                $starts[$i] = $starts[$i + 1];
                $ends[$i] = $ends[$i + 1];
            }
            $countOfDisciplines--;
        }

        function getCount()
        {
            return $countOfDisciplines;
        }

        function getHour($index)
        {
            return $hour[$index];
        }

        function getSpec($index)
        {
            return $spec[$index];
        }

        function getType($index)
        {
            return $typs[$index];
        }

        function getDiscipline($index)
        {
            return $disc[$index];
        }

        function getKurs($index)
        {
            return $kurs[$index];
        }

        function getNumberOfPair($index)
        {
            return $pair[$index];
        }

        function getStarts($index)
        {
            return $starts[$index];
        }

        function getEnds($index)
        {
            return $ends[$index];
        }
    }

    class Schedule
    {
       private $denominator = array(8);
       private $numerator = array(8);

        function __construct()
        {
            for ($i = 0; $i < 8; $i++)
            {
                $numerator[$i] = new Day();
                $denominator[$i] = new Day();
            }
        }

        function getNumeratorDay($day_of_week)
        {
            return $numerator[$day_of_week];
        }

        function getDenominatorDay($day_of_week)
        {
            return $denominator[$day_of_week];
        }

        //is_numerator: 0 - числитель, 1 - знаменатель, 2 - то и другое
        function addDiscipline($day_of_week, $hours, $speciality, $is_numerator, $typ, $disc, $kurs, $when, $strt, $end)
        {
            if ($speciality == null) return;
            switch ($is_numerator)
            {
                case 0:
                    {
                        $numerator[$day_of_week].addDisc($hours, $speciality, $typ, $disc, $kurs, $when, $strt, $end);
                    } break;
                case 1:
                    {
                        $denominator[$day_of_week].addDisc($hours, $speciality, $typ, $disc, $kurs, $when, $strt, $end);
                    } break;
                case 2:
                    {
                        $numerator[$day_of_week].addDisc($hours, $speciality, $typ, $disc, $kurs, $when, $strt, $end);
                        $denominator[$day_of_week].addDisc($hours, $speciality, $typ, $disc, $kurs, $when, $strt, $end);
                    } break;
            }
        }

        function getSpec($S)
        {
            $temp = "";
            for ($i = 0; $i < strlen($S); $i++)
            {
                if ($S[$i] == ';') break;
                $temp = $temp + $S[$i];
            }
            return $temp;
        }

        function getDisc($S)
        {
            $temp = "";
            $flag = 0;
            for ($i = 0; $i < strlen($S); $i++)
            {
                if ($S[$i] == ';') $flag++;
                if ($flag == 2 && $S[$i] != ';') $temp = $temp + $S[$i];
            }
            return $temp;
        }

        function getKurs($S)
        {
            $temp = "";
            $flag = 0;
            for ($i = 0; $i < strlen($S); $i++)
            {
                if ($S[$i] == ';') $flag++;
                if ($flag == 3 && $S[$i] != ';') $temp = $temp + $S[$i];
            }
            return $temp;
        }

        function getType($S)
        {
            $temp = "";
            $flag = 0;
            for ($i = 0; $i < strlen($S); $i++)
            {
                if ($S[$i] == ';') $flag++;
                if ($flag == 1 && $S[$i] != ';') $temp = $temp + $S[$i];
            }
            if (strlen($temp == 0)) $temp = $S;
            return intval($temp);
        }

        function getStart($S)
        {
            $temp = "";
            $flag = 0;
            for ($i = 0; $i < strlen($S); $i++)
            {
                if ($S[$i] == ';') $flag++;
                if ($flag == 4 && $S[$i] != ';') $temp = $temp + $S[$i];
            }
            //date?
            return ($temp);
        }

        function getEnd($S)
        {
            $temp = "";
            $flag = 0;
            for ($i = 0; $i < strlen($S); $i++)
            {
                if ($S[$i] == ';') $flag++;
                if ($flag == 5 && $S[$i] != ';') $temp = $temp + $S[$i];
            }
            //date?
            return ($temp);
        }

        function determineType($sid)
        {
            if ($sid == "Лекция") return 8;
            if ($sid == "Практика") return 10;
            if ($sid == "Лабораторная работа") return 12;
            if ($sid == "Консультация") return 19;
            return 0;
        }

        function LoadFromXML($fileName)
        {
            $xr=new XMLReader();
            $xr->open($fileName);
            while ($xr->read())
            {
                if ($xr->attributeCount > 0)
                {
                    $dw = 0;
                    if (substr($xr->getAttribute("time"), 0, 3) == "mon") $dw = 1;
                    if (substr($xr->getAttribute("time"), 0, 3) == "tue") $dw = 2;
                    if (substr($xr->getAttribute("time"), 0, 3) == "wed") $dw = 3;
                    if (substr($xr->getAttribute("time"), 0, 3) == "thr") $dw = 4;
                    if (substr($xr->getAttribute("time"), 0, 3) == "fri") $dw = 5;
                    if (substr($xr->getAttribute("time"), 0, 3) == "sat") $dw = 6;
                    $wh = 0;
                    if (substr($xr->getAttribute("time"), 4, 2) == "8_") $wh = 1;
                    if (substr($xr->getAttribute("time"), 4, 2) == "9_") $wh = 2;
                    if (substr($xr->getAttribute("time"), 4, 2) == "11") $wh = 3;
                    if (substr($xr->getAttribute("time"), 4, 2) == "13") $wh = 4;
                    if (substr($xr->getAttribute("time"), 4, 2) == "15") $wh = 5;
                    if (substr($xr->getAttribute("time"), 4, 2) == "16") $wh = 6;
                    if (substr($xr->getAttribute("time"), 4, 2) == "18") $wh = 7;

                    $denm = 1;
                    if ($xr->getAttribute("denm") == true) $denm = 0;

                    addDiscipline(
                        $dw, //day of week 1..6
                        2.0, //количество часов
                        $xr->getAttribute("spec"), //специальность
                        $denm, //числитель
                        determineType($xr->getAttribute("type")), //тип нагрузки
                        $xr->getAttribute("name"), //название дисциплины
                        $xr->getAttribute("kurs"), //номер курса
                        $wh, //когда проводится 1..7
                        date_create($xr->getAttribute("beg")), //дата начала проведения дисциплины
                        date_create($xr->getAttribute("end")));  //дата конца проведения дисциплины
                }
            }
        }
    }   
?>
