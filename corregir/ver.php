<?php
// Incluir la conexión a la base de datos
require_once('db.php');

// Verificar si el ID del evento ha sido proporcionado
if (!isset($_GET['id'])) {
    echo "<script> alert('ID del evento no definido.'); location.replace('http://localhost/corregir/'); </script>";
    $conn->close();
    exit;
}

// Obtener el ID del evento
$evento_id = $_GET['id'];

// Obtener los detalles del evento desde la base de datos
$sql = "SELECT * FROM eventos WHERE id = '$evento_id'";
$result = $conn->query($sql);

// Comprobación de errores en la consulta
if (!$result) {
    echo "<script> alert('Error en la consulta SQL: " . $conn->error . "'); location.replace('http://localhost/corregir/'); </script>";
    $conn->close();
    exit;
}

// Verificar si se encontró el evento
if ($result->num_rows > 0) {
    $evento = $result->fetch_assoc();
} else {
    echo "<script> alert('Evento no encontrado.'); location.replace('http://localhost/corregir/'); </script>";
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }
        .details {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .details strong {
            color: #007bff;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .actions a {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            margin: 0 10px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .actions a:hover {
            background-color: #218838;
        }
        .actions a.delete {
            background-color: #dc3545;
        }
        .actions a.delete:hover {
            background-color: #c82333;
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Restringir la fecha de entrega para que no se puedan seleccionar fechas pasadas
            let fechaEntregaInput = document.getElementById("fecha_entrega");
            let today = new Date().toISOString().split("T")[0];
            fechaEntregaInput.setAttribute("min", today);
        });
    </script>
</head>
<body>

    <div class="container">
        <h2>Detalles del Evento</h2>

        <!-- Mostrar detalles del evento -->
        <div class="details">
            <p><strong>Encargo:</strong> <?php echo htmlspecialchars($evento['encargo']); ?></p>
            <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($evento['nombre_cliente']); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($evento['descripcion']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo substr(htmlspecialchars($evento['numero_telefono']), 0, 10); ?></p>
            <p><strong>Fecha de Entrega:</strong> <input type="date" id="fecha_entrega" value="<?php echo htmlspecialchars($evento['fecha_entrega']); ?>" readonly></p>
            <p><strong>Hora de Entrega:</strong> <?php echo htmlspecialchars($evento['hora_entrega']); ?></p>
        </div>

        <!-- Botones de acción -->
        <div class="actions">
            <a href="edit_event.php?id=<?php echo $evento['id']; ?>">Editar Evento</a>
            <a href="dele_eve.php?id=<?php echo $evento['id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">Eliminar Evento</a>
        </div>

        <!-- Botón para volver al calendario -->
        <a href="http://localhost/corregir/" class="back">Volver al Calendario</a>
    </div>

</body>
</html>

<?php
$conn->close();
?>

