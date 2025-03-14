<?php 
// Configuración de la zona horaria
date_default_timezone_set("America/Mexico_City");

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "correccion"; // Se cambió el nombre de la base de datos
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener eventos desde la base de datos
$eventos = [];
$sql = "SELECT id, encargo, descripcion, numero_telefono, nombre_cliente, fecha_entrega, hora_entrega FROM eventos";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
} else {
    die("Error en la consulta: " . $conn->error);
}

// Obtener mes y año actual o proporcionado
$month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

// Calcular el primer día del mes y el número de días en el mes
$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$start_day = date('N', $first_day);

// Calcular mes anterior y siguiente
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}
$next_month = $month + 1;
$next_year = $year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Obtener la fecha actual
$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Joyería</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .calendar-container {
            display: inline-block;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            width: 14%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            position: relative;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .event {
            background-color: #ff6600;
            color: white;
            font-weight: bold;
            padding: 5px;
            margin-top: 5px;
            font-size: 12px;
            border-radius: 5px;
        }
        .button-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
    </style>
</head>

<body>
    <h2>Gestión de Actividades en la Joyería</h2>
    

    <!-- Botón para cambiar a vista de lista (antes de la fecha) -->
    <div class="button-container">
        <a href="lista.php" style="background-color: #007BFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Ver Lista de Eventos</a>
    </div>

    <form method="post" action="guarda_event.php" onsubmit="return validarFormulario()">
        <input type="text" name="encargo" placeholder="Encargo" required>
        <input type="text" name="descripcion" placeholder="Descripción" required>
        
        <!-- Validación de número de teléfono -->
        <input type="text" name="numero_telefono" placeholder="Número de Teléfono" pattern="\d{10}" title="Debe ser un número de 10 dígitos" required>
        
        <input type="text" name="nombre_cliente" placeholder="Nombre del Cliente" required>
        
        <!-- Bloquear fechas anteriores al día actual -->
        <input type="date" name="fecha_entrega" min="<?php echo $today; ?>" required>
        
        <!-- Validar hora entre 8:00 AM y 2:00 PM -->
        <input type="time" name="hora_entrega" min="08:00" max="14:00" required>
        
        <button type="submit">Guardar</button>
    </form>
    <br>
    
    <div class="calendar-container">
        <table>
            <tr>
                <th><a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>">&#8592;</a></th>
                <th colspan="5"><?php echo date('F Y', $first_day); ?></th>
                <th><a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>">&#8594;</a></th>
            </tr>
            <tr>
                <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
            </tr>
            <tr>
                <?php
                $day_counter = 0;
                for ($i = 1; $i < $start_day; $i++) {
                    echo "<td></td>";
                    $day_counter++;
                }
                for ($day = 1; $day <= $days_in_month; $day++) {
                    $current_date = sprintf("%04d-%02d-%02d", $year, $month, $day);
                    echo "<td>$day";
                    foreach ($eventos as $evento) {
                        if ($current_date == $evento['fecha_entrega']) {
                            echo "<div class='event'>";
                            echo "<a href='ver.php?id={$evento['id']}' style='color: white;'>{$evento['encargo']} ({$evento['hora_entrega']})</a>";
                            echo "</div>";
                        }
                    }
                    echo "</td>";
                    $day_counter++;
                    if ($day_counter % 7 == 0) {
                        echo "</tr><tr>";
                    }
                }
                while ($day_counter % 7 != 0) {
                    echo "<td></td>";
                    $day_counter++;
                }
                ?>
            </tr>
        </table>
    </div>

    <script>
        // Función de validación en el lado del cliente
        function validarFormulario() {
            var telefono = document.querySelector('input[name="numero_telefono"]').value;
            if (!/^\d{10}$/.test(telefono)) {
                alert("El número de teléfono debe tener exactamente 10 dígitos.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
