<?php


// try {
//     $conexion = mysqli_connect('localhost', 'root', '', 'ia_bd');
//     $question = $_POST['question'];
//     $sql = "SELECT answer FROM ejemplo WHERE question LIKE '%$question%'";
//     $resultado = mysqli_query($conexion, $sql);
//     $salida = "";

//     if ($resultado->num_rows > 0) {
//         while ($fila = $resultado->fetch_assoc()) {
//             $salida = $fila['answer']; // Agregamos un salto de línea después de cada respuesta
//         }
//     } else {
//         // Si no hay registros, omitimos las primeras 2 palabras
//         $palabras = explode(' ', $question);
//         $question = implode(' ', array_slice($palabras, 2)); // Tomamos todas las palabras excepto las dos primeras

//         $sql = "SELECT answer FROM ejemplo WHERE question LIKE '%$question%'";
//         $resultado = mysqli_query($conexion, $sql);

//         if ($resultado->num_rows > 0) {
//             while ($fila = $resultado->fetch_assoc()) {
//                 $salida = $fila['answer']; // Agregamos un salto de línea después de cada respuesta
//             }
//         } else {
//             $salida = 0;
//         }
//     }

//     echo $salida;
// } catch (\Exception $e) {
//     echo 0;   
// }

try {
    $conexion = mysqli_connect('localhost', 'root', '', 'ia_bd');
    $question = $_POST['question'];
    if (preg_match('/hora|dia|mes|fecha|año/i', $question)) {

        if (strpos($question, 'hora') !== false) {
            date_default_timezone_set('America/Guatemala');
            $res = date("H:i:s");
        } elseif (strpos($question, 'dia') !== false) {
            // Si la pregunta contiene "dia", devolver el día de la semana actual
            // $res = date("l");
            setlocale(LC_TIME, 'es_ES');
            $res = strftime("%A");
        } elseif (strpos($question, 'fecha') !== false) {
            // Si la pregunta contiene "fecha", devolver la fecha actual
            $res = date("Y-m-d"); // Formato YYYY-MM-DD
        } elseif (strpos($question, 'mes') !== false) {
            // $res = date("F");
            // Obtener el nombre del mes en español
            $meses = array(
                1 => 'enero',
                2 => 'febrero',
                3 => 'marzo',
                4 => 'abril',
                5 => 'mayo',
                6 => 'junio',
                7 => 'julio',
                8 => 'agosto',
                9 => 'septiembre',
                10 => 'octubre',
                11 => 'noviembre',
                12 => 'diciembre'
            );
            
            $mes_actual = date('n');
            $res = 'El mes de '.$meses[$mes_actual];
        } elseif (strpos($question, 'año') !== false) {
            // Si la pregunta contiene "año", devolver el año actual
            $res = date("Y"); // Año en formato de cuatro dígitos (por ejemplo, "2024")
        } else {
            $res = 0;
        }
        echo $res;
    } else {
        // Consultar la base de datos para obtener todas las preguntas
        $sql = "SELECT question, answer FROM ejemplo";
        $resultado = mysqli_query($conexion, $sql);
    
        // Inicializar variables para almacenar la mejor coincidencia y la distancia mínima
        $best_match = "";
        $min_distance = PHP_INT_MAX;
    
        // Calcular la distancia de Levenshtein para cada pregunta y encontrar la más cercana
        while ($fila = $resultado->fetch_assoc()) {
            $pregunta_bd = $fila['question'];
            $answer = $fila['answer'];
    
            $distance = levenshtein($question, $pregunta_bd);
    
            // Si la distancia actual es menor que la mínima encontrada hasta ahora, actualizar
            if ($distance < $min_distance) {
                $min_distance = $distance;
                $best_match = $answer;
            }
        }
    
        // Si la distancia mínima encontrada es menor que cierto umbral, devolver la respuesta
        // De lo contrario, devolver 0 indicando que no se encontró ninguna coincidencia suficientemente cercana
        if ($min_distance <= 3) { // Puedes ajustar este valor según tus necesidades
            echo $best_match;
        } else {
            echo 0;
        }
    }
} catch (\Exception $e) {
    echo 0;   
}
?>
