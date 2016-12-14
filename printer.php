<html>
<head>
<meta http-equiv="Content-Type" content="no-cache; charset=utf-8">
<script language="JavaScript">
function GetVerticalLayout()
{
    var fontFamily = "Arial"; /* задаем шрифт */
    var fontSize = 12; /* задаем размер шрифта */
    var notIE = !(navigator.appVersion.indexOf("MSIE") != -1 && navigator.systemLanguage);
    var supportSVG = document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1");

    if (notIE && supportSVG)
    {
        var td = document.getElementsByTagName("th");
        var objElement = document.createElement("object");
        for(i = 0; i < td.length; i++)
        {
            if (td[i].className.match(/vertical/i))
            {
                var text = td[i].innerHTML;
                var h = td[i].clientHeight;
                var w = td[i].clientWidth;

                var obj = objElement.cloneNode(true);
                obj.height = (h > w) ? h : w;
                obj.type = "image/svg+xml";
                obj.width = fontSize + 2;
                obj.data = "data:image/svg+xml;charset/utf-8,<svg xmlns='http://www.w3.org/2000/svg'><text x='" + (- obj.height/2) + "' y='" + fontSize + "' style='font-family:" + fontFamily + "; font-size:" + fontSize + "px; text-anchor:middle' transform='rotate(-90)'>" + text + "</text></svg>";
                td[i].replaceChild(obj, td[i].firstChild);
            }
        }
    }
}
</script>


<style type="text/css">
table.example{
	font-family:Arial;
	font-size:12px;
	border-collapse:collapse;
}
	table.example td{
		padding:5px;
		border:1px solid #cc0;
		white-space:nowrap;
		overflow: hidden;
	}
	
tr.total{
	padding:5px;
	border:1px solid #cc0;
	white-space:nowrap;
	overflow: hidden;
	background:#009900;
}

th.vertical{
	padding:5px;
	border:1px solid #cc0;
	writing-mode: tb-rl;
	filter: flipH flipV;
	background:#fff;
	text-align:center;
	font-size:12px;
	font-family:Arial;
	height:200px;
}
</style>
<head>

<body onload="GetVerticalLayout()">

<center><a href="Temp.xltx">Excel версия журнала</a></center>

<?php
$sem = $_POST['sem1'];

include 'PHPExcel/IOFactory.php';
$inputFileName = './example.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$dom = new domDocument("1.0", "utf-8");
$root = $dom->createElement("data");
$dom->appendChild($root);

for($i = 2; $i < count($sheetData); $i++) {
    $j = 0;

    $el = $dom->createElement("row");
    $root->appendChild($el);
    foreach ($sheetData[$i] as $h) {
        $j++;
        $el1 = $dom->createElement("t" . strval($j), $h);
        $el->appendChild($el1);
    }
}
$dom->save("./example.xml");
if($sem == "Autumn") {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include 'config.php';
    include 'Graph.php';
    include 'Loads.php';
    include 'MaxFlow.php';
    include 'Schedule.php';
    include 'program.php';

    $P = new Program();
    $a = array('example.xml','ttAutumn.xml');
    echo $P->Main($a);    
}

?>
</body>
</html>