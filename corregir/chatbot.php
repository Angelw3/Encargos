<?php
require_once('db.php'); // Conexión a la base de datos

// Simulamos algunos eventos como ejemplo (puedes usar tu base de datos real)
$eventos = [
    ["encargo" => "Encargo 1", "fecha_entrega" => "2025-03-10"],
    ["encargo" => "Encargo 2", "fecha_entrega" => "2025-03-15"],
    ["encargo" => "Encargo 3", "fecha_entrega" => "2025-04-05"],
    ["encargo" => "Encargo 4", "fecha_entrega" => "2025-03-20"]
];

// Obtener eventos de la base de datos (si fuera necesario)
// $sql = "SELECT * FROM eventos ORDER BY fecha_entrega";
// $result = $conn->query($sql);
// $eventos = [];
// while ($evento = $result->fetch_assoc()) {
//     $eventos[] = $evento;
// }
// $conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_message = strtolower(trim($_POST['user_message']));

    $response = "No encontré eventos relacionados.";

    if (strpos($user_message, "encargos en el mes de") !== false) {
        // Extraer el mes y el año del mensaje
        preg_match("/encargos en el mes de (\w+) de (\d{4})/", $user_message, $matches);
        
        if (count($matches) == 3) {
            $month_name = $matches[1];
            $year = $matches[2];

            // Mapeo de meses a números
            $months = [
                "enero" => "01", "febrero" => "02", "marzo" => "03", "abril" => "04", "mayo" => "05", "junio" => "06",
                "julio" => "07", "agosto" => "08", "septiembre" => "09", "octubre" => "10", "noviembre" => "11", "diciembre" => "12"
            ];

            $month = $months[strtolower($month_name)] ?? null;
            if ($month) {
                // Filtrar eventos por mes y año
                $filtered_events = array_filter($eventos, function($e) use ($month, $year) {
                    return substr($e['fecha_entrega'], 0, 7) === "$year-$month";
                });

                if ($filtered_events) {
                    $event_names = array_map(function($e) { return $e['encargo']; }, $filtered_events);
                    $response = "Tienes los siguientes encargos en $month_name de $year: " . implode(", ", $event_names);
                } else {
                    $response = "No tienes encargos en $month_name de $year.";
                }
            } else {
                $response = "No pude entender el mes o el año proporcionado. Por favor usa un formato como 'marzo de 2025'.";
            }
        } else {
            $response = "Por favor, proporciona un mes y un año (ejemplo: 'encargos de marzo de 2025').";
        }
    }

    echo json_encode(["response" => $response]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot de Recordatorios</title>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .chat-box {
            border: 1px solid #ddd;
            padding: 10px;
            height: 300px;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .input-box {
            display: flex;
        }
        .input-box input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .input-box button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .input-box button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chatbot de Recordatorios</h2>
        <div class="chat-box" id="chat-box">
            <p><strong>Chatbot:</strong> ¡Hola! Pregúntame por eventos próximos, de un mes o un día específico.</p>
        </div>
        <div class="input-box">
            <input type="text" id="user-input" placeholder="Escribe un mensaje...">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>

    <script>
        function sendMessage() {
            let input = document.getElementById("user-input");
            let message = input.value.trim();
            if (message === "") return;

            let chatBox = document.getElementById("chat-box");
            chatBox.innerHTML += `<p><strong>Tú:</strong> ${message}</p>`;
            input.value = "";

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_message=${encodeURIComponent(message)}`,
            })
            .then(response => response.json())
            .then(data => {
                chatBox.innerHTML += `<p><strong>Chatbot:</strong> ${data.response}</p>`;
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => {
                chatBox.innerHTML += `<p><strong>Chatbot:</strong> Ocurrió un error.</p>`;
                console.error(error);
            });
        }
    </script>
</body>
</html>
