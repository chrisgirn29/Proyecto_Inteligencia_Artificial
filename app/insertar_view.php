<?php
    try {
        $conexion=mysqli_connect('localhost','root','','ia_bd');
        $question=$_POST['question'];
        $answer=$_POST['answer'];
        $sql="insert into ejemplo values('$question','$answer')";
        echo mysqli_query($conexion,$sql);
    } catch (\Exception $e) {
        echo "Error: " . $e;   
    }

?>