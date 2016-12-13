<?php
    @mkdir("./loads/", 0777);
    $uploaddir = './loads/';
    if($_FILES['load']['size'] > 0) {
        $uploadfile = $uploaddir . basename($_FILES['load']['name']);
        $upd = basename($_FILES['load']['name']);
        $ind = strpos($upd, '.');
        $upd = substr($upd, 0, $ind);
        if (move_uploaded_file($_FILES['load']['tmp_name'], $uploadfile)) {
        } else {
        }

        if (copy($uploadfile, './example.xlsx')) {
        } else {
        }
        echo $uploadfile;
        header('Location: sched.php?semestr=Autumn&nams=' . $upd);
    } else {
        $ind = strpos($_POST['sweet'], '.');
        $upd = substr($_POST['sweet'], 0, $ind);
        if (copy('./loads/' . $upd . '.xlsx', './example.xlsx')) {
        } else {
        }
        header('Location: sched.php?semestr=Autumn&nams=' . $upd);
    }
?>