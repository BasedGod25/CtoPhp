<?php
	internal class Config
    {
        $holidays[];
        $count = 0;

        $changes[,];
        $count_changes = 0;

        $s1 = new DateTime();
        $s1->setDate(date("Y"), 9, 1);
        $e1 = new DateTime();
        $e1->setDate(date("Y"), 12, 31);
        $s4 = new DateTime();
        $s4->setDate(date("Y"), 9, 1);
        $e4 = new DateTime();
        $e4->setDate(date("Y"), 12, 31);

        $autumnSemestr = false;

        function LoadFromFile($fileName)
        {
            
        }

        function addHoliday($dt)
        {
            $holidays[$count++] = $dt;
        }


        function getChangesCount() {
            return $count_changes;
        }

        function getHolidays() {
            return $holidays;
        }

        function getChanges() {
            return $changes;
        }

        function updateDateTime() {
            $count = 0;
            //for (int i = 0; i < listBox1.Items.Count; i++)
            //    holidays[count++] = Convert.ToDateTime(listBox1.Items[i]);
        }

        //c1s - course 1st - 3rd start, c1e - course 1st - 3rd end
        //c4s - course 4th - 5th start, c4e - course 4th - 5th end
        function setSemesterStart($c1s, $c1e, $c4s, $c4e) {
            $s1 = $c1s;
            $e1 = $c1e;

            $s4 = $c4s;
            $e4 = $c4e;
        }

        function isAutumn() {
            return $autumnSemestr;
        }

        function getNum()
        {
            return true; //semestr start with nominator
        }

        //returns maximum work time in a day
        function getWorkTime() {
            return 14;
        }

        function getStart() {
            return $s1;
        }

        function getEnd() {
            return $e1;
        }

        function getStart4() {
            return $s4;
        }

        function getEnd4() {
            return $e4;
        }

        function setSemestr($S)
        {
            if ($S.Equals("Autumn"))
            {
                $s1->setDate(date("Y"), 9, 1);
				$e1->setDate(date("Y"), 12, 31);
                $s4->setDate(date("Y"), 9, 1);
                $e4->setDate(date("Y"), 12, 31);
            }
            else
            {
				$s1->setDate(date("Y")+1, 2, 7);
				$e1->setDate(date("Y")+1, 6, 30);
                $s4->setDate(date("Y")+1, 2, 7);
                $e4->setDate(date("Y")+1, 6, 30);


                //$s1 = new DateTime(System.DateTime.Today.Year + 1, 2, 7);
                //$e1 = new DateTime(System.DateTime.Today.Year + 1, 6, 30);
                //$s4 = new DateTime(System.DateTime.Today.Year + 1, 2, 7);
                //$e4 = new DateTime(System.DateTime.Today.Year + 1, 6, 30);
            }
        }
    }
?>