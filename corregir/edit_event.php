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

// Obtener los detalles del evento desde la base de datos
$sql = "SELECT * FROM eventos WHERE id = '$evento_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $evento = $result->fetch_assoc();
} else {
    echo "<script> alert('Evento no encontrado.'); location.replace('http://localhost/corregir/'); </script>";
    $conn->close();
    exit;
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los nuevos datos del formulario
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

    // Actualizar el evento en la base de datos
    $sql_update = "UPDATE eventos 
                   SET encargo = '$encargo', descripcion = '$descripcion', numero_telefono = '$numero_telefono', nombre_cliente = '$nombre_cliente', fecha_entrega = '$fecha_entrega', hora_entrega = '$hora_entrega' 
                   WHERE id = '$evento_id'";

    if ($conn->query($sql_update) === TRUE) {
        // Si la actualización es exitosa, redirigir al calendario
        echo "<script> alert('Evento actualizado con éxito.'); location.replace('http://localhost/corregir/'); </script>";
    } else {
        echo "Error al actualizar el evento: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="date"], input[type="time"] {
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        .back {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }
        .back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Editar Evento</h2>

        <!-- Formulario para editar el evento -->
<form method="POST">
    <label for="encargo"><strong>Encargo:</strong></label>
    <input type="text" id="encargo" name="encargo" value="<?php echo htmlspecialchars($evento['encargo']); ?>" required>

    <label for="descripcion"><strong>Descripción:</strong></label>
    <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($evento['descripcion']); ?>" required>

    <label for="numero_telefono"><strong>Número de Teléfono:</strong></label>
    <input type="text" id="numero_telefono" name="numero_telefono" value="<?php echo htmlspecialchars($evento['numero_telefono']); ?>" pattern="^\d{10}$" title="El número debe tener exactamente 10 dígitos." required>

    <label for="nombre_cliente"><strong>Nombre del Cliente:</strong></label>
    <input type="text" id="nombre_cliente" name="nombre_cliente" value="<?php echo htmlspecialchars($evento['nombre_cliente']); ?>" required>

    <label for="fecha_entrega"><strong>Fecha de Entrega:</strong></label>
    <input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php echo $evento['fecha_entrega']; ?>" min="<?php echo date('Y-m-d'); ?>" required>

    <label for="hora_entrega"><strong>Hora de Entrega:</strong></label>
    <input type="time" id="hora_entrega" name="hora_entrega" value="<?php echo $evento['hora_entrega']; ?>" min="08:00" max="14:00" required>

    <button type="submit">Actualizar Evento</button>
</form>

        <a href="http://localhost/corregir/" class="back">Volver al Calendario</a>
    </div>

</body>
</html>
