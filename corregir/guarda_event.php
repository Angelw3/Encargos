<?php
// Incluir la conexión a la base de datos
include('db.php');

// Configuración de la zona horaria
date_default_timezone_set("America/Mexico_City");

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $encargo = $_POST['encargo'];
    $descripcion = $_POST['descripcion'];
    $numero_telefono = $_POST['numero_telefono'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $hora_entrega = $_POST['hora_entrega'];

    // Escapar los datos para prevenir inyección SQL
    $encargo = $conn->real_escape_string($encargo);
    $descripcion = $conn->real_escape_string($descripcion);
    $numero_telefono = $conn->real_escape_string($numero_telefono);
    $nombre_cliente = $conn->real_escape_string($nombre_cliente);
    $fecha_entrega = $conn->real_escape_string($fecha_entrega);
    $hora_entrega = $conn->real_escape_string($hora_entrega);

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO eventos (encargo, descripcion, numero_telefono, nombre_cliente, fecha_entrega, hora_entrega) 
            VALUES ('$encargo', '$descripcion', '$numero_telefono', '$nombre_cliente', '$fecha_entrega', '$hora_entrega')";
    
    if ($conn->query($sql) === TRUE) {
        // Si la inserción es exitosa, redirigir directamente a la página que contiene el calendario actualizado
        header("Location: http://localhost/corregir/"); // Redirigir a la página con los eventos actualizados
        exit(); // Detener el script para asegurar que no se ejecute más código
    } else {
        // Si ocurre un error en la inserción
        echo "Error al guardar el evento: " . $conn->error;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
