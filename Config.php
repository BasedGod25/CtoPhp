<?php
	class Config
    {
        public $holidays=array();
        public $count = 0;

        public $changes=array();
        public $count_changes = 0;

        public $s1;// = new DateTime();
        public $e1;// = new DateTime();
        public $s4;// = new DateTime();
        public $e4;// = new DateTime();
        

        public $autumnSemestr = false;

        function __construct()
        {
            $this->s1 = new DateTime();
            $this->e1 = new DateTime();
            $this->s4 = new DateTime();
            $this->e4 = new DateTime();

            $this->s1->setDate(date("Y"), 9, 1);
            $this->e1->setDate(date("Y"), 12, 31);
            $this->s4->setDate(date("Y"), 9, 1);
            $this->e4->setDate(date("Y"), 12, 31);
        }

        function LoadFromFile($fileName)
        {
            
        }

        function addHoliday($dt)
        {
            $holidays[$this->count++] = $dt;
        }


        function getChangesCount() {
            return $this->count_changes;
        }

        function getHolidays() {
            return $this->holidays;
        }

        function getChanges() {
            return $this->changes;
        }

        function updateDateTime() {
            $count = 0;
            //for (int i = 0; i < listBox1.Items.Count; i++)
            //    holidays[count++] = Convert.ToDateTime(listBox1.Items[i]);
        }

        //c1s - course 1st - 3rd start, c1e - course 1st - 3rd end
        //c4s - course 4th - 5th start, c4e - course 4th - 5th end
        function setSemesterStart($c1s, $c1e, $c4s, $c4e) {
            $this->s1 = $c1s;
            $this->e1 = $c1e;

            $this->s4 = $c4s;
            $this->e4 = $c4e;
        }

        function isAutumn() {
            return $this->autumnSemestr;
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
            return $this->s1;
        }

        function getEnd() {
            return $this->e1;
        }

        function getStart4() {
            return $this->s4;
        }

        function getEnd4() {
            return $this->e4;
        }

        function setSemestr($S)
        {
            if ($S =="Autumn")
            {
                $this->s1->setDate(date("Y"), 9, 1);
				$this->e1->setDate(date("Y"), 12, 31);
                $this->s4->setDate(date("Y"), 9, 1);
                $this->e4->setDate(date("Y"), 12, 31);
            }
            else
            {
				$this->s1->setDate(date("Y")+1, 2, 7);
				$this->e1->setDate(date("Y")+1, 6, 30);
                $this->s4->setDate(date("Y")+1, 2, 7);
                $this->e4->setDate(date("Y")+1, 6, 30);

            }
        }
    }
?>