<?php
$conexion = mysqli_connect('localhost', 'root', '', 'ia_bd');
$question = $_POST['question'];
$sql = "SELECT answer FROM ejemplo WHERE question LIKE '%$question%'";
$resultado = mysqli_query($conexion, $sql);
$salida = "";

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $salida .= $fila['answer'] . "<br>"; // Agregamos un salto de línea después de cada respuesta
    }
} else {
    // Si no hay registros, omitimos las primeras 2 palabras
    $palabras = explode(' ', $question);
    $question = implode(' ', array_slice($palabras, 2)); // Tomamos todas las palabras excepto las dos primeras

    $sql = "SELECT answer FROM ejemplo WHERE question LIKE '%$question%'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $salida .= $fila['answer'] . "<br>"; // Agregamos un salto de línea después de cada respuesta
        }
    } else {
        $salida .= "No tengo registros sobre la informacion que necesitas saber!";
        $salida .= "<script>
                        if (confirm('¿Deseas ayudarme a aprender?')) {
                            window.open('ventana_insertar.html', '_blank');
                        }
                   </script>";
    }
}

echo $salida;
?>
