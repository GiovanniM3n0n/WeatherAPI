<?php
require_once __DIR__ . '/../config/database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$apiKey = "11a21a0613eb7485f911d68b0aee3310";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['city'])) {
    $city = urlencode($_GET['city']);
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=pt";

    $response = @file_get_contents($url);

    if ($response === FALSE) {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao buscar os dados do OpenWeatherMap"]);
        exit;
    }

    $weatherData = json_decode($response, true);

    if (isset($weatherData['main'])) {
        $temperature = $weatherData['main']['temp'];
        $humidity = $weatherData['main']['humidity'];
        $windSpeed = $weatherData['wind']['speed'];
        $weatherDescription = $weatherData['weather'][0]['description'];

        $sql = "INSERT INTO weather_searches (city, temperature, humidity, wind_speed, weather_description) 
                VALUES (:city, :temperature, :humidity, :wind_speed, :weather_description)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':city' => $_GET['city'],
            ':temperature' => $temperature,
            ':humidity' => $humidity,
            ':wind_speed' => $windSpeed,
            ':weather_description' => $weatherDescription
        ]);

        echo json_encode([
            "city" => $_GET['city'],
            "temperature" => $temperature,
            "humidity" => $humidity,
            "wind_speed" => $windSpeed,
            "weather_description" => $weatherDescription,
            "message" => "Dados salvos no banco com sucesso!"
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Cidade não encontrada"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=pt";

    $response = @file_get_contents($url);

    if ($response === FALSE) {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao buscar os dados do OpenWeatherMap"]);
        exit;
    }

    $weatherData = json_decode($response, true);

    if (isset($weatherData['main'])) {
        echo json_encode([
            "city" => $weatherData['name'],
            "temperature" => $weatherData['main']['temp'],
            "humidity" => $weatherData['main']['humidity'],
            "wind_speed" => $weatherData['wind']['speed'],
            "weather_description" => $weatherData['weather'][0]['description']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Localização não encontrada"]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['history'])) {
    $stmt = $pdo->query("SELECT * FROM weather_searches ORDER BY created_at DESC LIMIT 10");
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($history);
    exit;
}


http_response_code(400);
echo json_encode(["error" => "Rota inválida ou parâmetro faltando"]);
