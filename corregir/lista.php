<?php
require_once('db.php'); // Asegúrate de que esta ruta sea correcta

// Obtener todos los eventos de la base de datos
$sql = "SELECT * FROM eventos ORDER BY fecha_entrega";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-links {
            text-align: center;
        }
        .action-links a {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .action-links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Lista de Eventos</h2>

        <!-- Verificar si hay eventos -->
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Encargo</th>
                        <th>Descripción</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Fecha de Entrega</th>
                        <th>Hora de Entrega</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($evento = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($evento['encargo']); ?></td>
                            <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($evento['nombre_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($evento['numero_telefono']); ?></td>
                            <td><?php echo htmlspecialchars($evento['fecha_entrega']); ?></td>
                            <td><?php echo htmlspecialchars($evento['hora_entrega']); ?></td>
                            <td class="action-links">
                                <!-- Enlaces para ver, editar y eliminar el evento -->
                                <a href="ver.php?id=<?php echo $evento['id']; ?>">Ver</a> |
                                <a href="dele_eve.php?id=<?php echo $evento['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay eventos registrados en la base de datos.</p>
        <?php endif; ?>

        <div class="action-links">
            <a href="http://localhost/corregir/">Volver al Calendario</a>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
