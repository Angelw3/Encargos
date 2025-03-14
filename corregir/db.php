<?php
// db.php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "correccion"; // Actualizado el nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
