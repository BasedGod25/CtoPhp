<?php
  class Load
    {
        public $countOfTypes = 31;
        public $countOfSubjects = 0;
        public $z = array(64, 64);
        public $names = array(64);
        public $spect = array(64);
        public $courses = array(64);

        public  $name_of_lecturer = "";
        public $isAutumn = true;

        function Load($is_autumn)
        {
            $isAutumn = $is_autumn;
        }

        function LoadFromXML($fileName)
        {
            //XMLReader xr = new XmlTextReader(new FileStream(fileName, FileMode.Open));
            $xr=new XMLReader();
            $xr->open($fileName);
            $n = -1;
            $arr = array(256, 256);
            while ($xr->read())
            {
                if ($xr->nodeType == XMLReader::ELEMENT && $xr->localName == "row")
                {
                    $n++;
                    $key = 0;
                    while ($xr->read())
                    {
                        if ($xr->nodeType == XMLReader::END_ELEMENT && $xr->localName == "row") break;
                        if ($xr->nodeType == XMLReader::ELEMENT)
                        {
                            $key = intval(substr($xr->localName, 1));
                        }
                        if ($xr->nodeType == XMLReader::TEXT)
                        {
                            $arr[$n][$key] = $xr->value;
                        }
                    }
                }
            }
            $n++;
            $cnt = 1;
            for ($i = 0; $i < $n; $i++)
            {
                if ($arr[$i][6] == null) $arr[$i][6] = "2";
                $sem = intval($arr[$i][6]);
                if (($sem % 2 == 1) != $isAutumn) continue;

                $names[$cnt] = $arr[$i][1];
                $spect[$cnt] = $arr[$i][3];
                $courses[$cnt] = $arr[$i][5];

                if ($spect[$cnt] == null)
                    $spect[$cnt] = $arr[$i][4];
                for ($j = 1; $j <= $countOfTypes; $j++)
                {
                    if ($arr[$i][$j + 4] == null || strlen($arr[$i][$j + 4]) == 0)
                        $z[$cnt][$j] = 0;
                    else
                    {
                        $res = 0.0;
                        $arr[$i][$j + 4] = str_replace('.', ',', $arr[$i][$j + 4]);
                        // tut nado tryparse if (floatval($arr[$i, $j + 4])
                        //{
                            $z[$cnt][$j] = floatval($arr[$i][$j + 4]);
                        //}
                        //else
                        //{
                        //    Console.WriteLine(arr[i, j + 4]);
                        //}
                    }
                }
                $cnt++;
            }
            $countOfSubjects = $cnt - 1;
        }
    }

?>