<html>
<head>
<meta http-equiv="Content-Type" content="no-cache; charset=UTF-8">
</head>

<script language="JavaScript">
function trySend() {
    document.raba.submit();
}
</script>

<body>
    
    <center><h1>Заполнения журнала учета рабочего времени преподавателя</h1></center>
    <form name="raba" action="load.php" method="post" enctype="multipart/form-data">
        <table>
            <tr><td>1.</td><td>Для заполнения журнала учета рабочего времени необходим файл нагрузки в формате Excel</td><td></td></tr>
            <tr><td><br></td></tr>
            <tr><td>2.</td><td>Воспользуйтесь примером: </td><td><a href="example.xlsx">Пример заполнения нагрузки в Excel</a></td></tr>
            <tr><td><br></td></tr>
            <tr><td>3.</td><td>Загрузите файл: </td><td><input type="file" name="load" /></td></tr>
            <tr><td><br></td></tr>
            <tr><td>4.</td><td>Или воспользуйтесь ранее загруженным: 
                </td><td><select name="sweet">
                <?php
                        $a = scandir("./loads/");
                        foreach($a as $item) {
                                if(substr($item, 0, 1) != "." && strlen($item) > 0) {
                                        echo "<option>" . $item . "</option>";
                                }
                        }
                ?>
                </select>
            </td></tr>
            <!--<input type="hidden" name="passwd"/>-->
            <tr><td><br></td></tr>
			
            <tr><td>5.</td><td>Выберите семестр: </td><td><select name="semestr" id="semestr"><option>Autumn</option><option>Spring</option></select></td></tr>
			<tr><td><br></td></tr>
            <tr><td>6.</td><td>Перейдите к составлению расписания: </td><td><input type="button" value="Send" onclick="trySend();" /></td></tr>
        </table>
    </form>
</body>
</html>