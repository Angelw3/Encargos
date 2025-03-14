<?php
require_once('db.php'); // Asegúrate de que esta ruta sea correcta

// Verificar si el ID del evento ha sido proporcionado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script> alert('ID del evento no definido o inválido.'); location.replace('http://localhost/corregir/'); </script>";
    $conn->close();
    exit;
}

// Obtener el ID del evento
$evento_id = (int) $_GET['id']; // Asegurarse de que el ID sea un número entero

// Eliminar el evento de la base de datos
$sql_delete = "DELETE FROM eventos WHERE id = '$evento_id'";

if ($conn->query($sql_delete) === TRUE) {
    // Si la eliminación es exitosa, redirigir al calendario con un mensaje de éxito
    echo "<script> alert('Evento eliminado con éxito.'); location.replace('http://localhost/corregir/'); </script>";
} else {
    // Si ocurre un error, mostrar mensaje de error
    echo "<script> alert('Error al eliminar el evento: " . $conn->error . "'); location.replace('http://localhost/corregir/'); </script>";
}

$conn->close();
?>
