<?php
require_once __DIR__ . '/../config/database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$apiKey = "SUA_API_KEY_AQUI";

function fetchWeatherData($url) {
    $response = @file_get_contents($url);
    if ($response === FALSE) {
        $error = error_get_last();
        http_response_code(500);
        echo json_encode([
            "error"   => "Erro ao buscar os dados do OpenWeatherMap", 
            "details" => $error['message']
        ]);
        exit;
    }
    return json_decode($response, true);
}

function saveSearchHistory($pdo, $weatherData) {
    $stmt = $pdo->prepare("
        INSERT INTO weather_searches 
            (city, temperature, humidity, wind_speed, cloudiness, weather_description)
        VALUES 
            (:city, :temperature, :humidity, :wind_speed, :cloudiness, :weather_description)
    ");
    $stmt->execute([
        ':city'                => $weatherData['name'],
        ':temperature'         => $weatherData['main']['temp'],
        ':humidity'            => $weatherData['main']['humidity'],
        ':wind_speed'          => $weatherData['wind']['speed'],
        ':cloudiness'          => $weatherData['clouds']['all'],
        ':weather_description' => $weatherData['weather'][0]['description']
    ]);
}

function fetchCitySuggestions($query, $apiKey) {
    $query = urlencode(trim(urldecode($query)));
    $geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q={$query}&limit=5&appid={$apiKey}";
    $geoData = fetchWeatherData($geoUrl);

    if (!empty($geoData)) {
        $uniqueCities = [];
        foreach ($geoData as $city) {
            $cityKey = "{$city['name']}-{$city['country']}";
            if (!isset($uniqueCities[$cityKey])) {
                $uniqueCities[$cityKey] = [
                    "name"    => $city['name'],
                    "state"   => $city['state'] ?? "N/A",
                    "country" => $city['country']
                ];
            }
        }
        echo json_encode(array_values($uniqueCities));
    } else {
        echo json_encode([]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido"]);
    exit;
}

$action = null;
if (isset($_GET['query'])) {
    $action = 'suggestions';
} elseif (isset($_GET['forecast']) && isset($_GET['city'])) {
    $action = 'forecast';
} elseif (isset($_GET['lat'], $_GET['lon'])) {
    $action = 'latlon';
} elseif (isset($_GET['city'])) {
    $action = 'city';
} elseif (isset($_GET['history'])) {
    $action = 'history';
}

switch ($action) {
    case 'suggestions':
        fetchCitySuggestions($_GET['query'], $apiKey);
        break;

    case 'forecast':
        $city = urlencode(trim(urldecode($_GET['city'])));
        $url  = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$apiKey}&units=metric&lang=pt";
        $forecastData = fetchWeatherData($url);

        if (!isset($forecastData['list'])) {
            http_response_code(404);
            echo json_encode(["error" => "Previsão não encontrada"]);
            exit;
        }

        $dailyForecast = [];
        foreach ($forecastData['list'] as $entry) {
            $date = substr($entry['dt_txt'], 0, 10);
            if (!isset($dailyForecast[$date])) {
                $dailyForecast[$date] = [
                    "date"        => $date,
                    "minTemp"     => $entry['main']['temp'],
                    "maxTemp"     => $entry['main']['temp'],
                    "description" => $entry['weather'][0]['description']
                ];
            } else {
                $dailyForecast[$date]["minTemp"] = min($dailyForecast[$date]["minTemp"], $entry['main']['temp']);
                $dailyForecast[$date]["maxTemp"] = max($dailyForecast[$date]["maxTemp"], $entry['main']['temp']);
            }
        }
        echo json_encode(array_values(array_slice($dailyForecast, 0, 5)));
        break;

    case 'latlon':
        $lat = $_GET['lat'];
        $lon = $_GET['lon'];
        $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=pt";
        $weatherData = fetchWeatherData($url);

        if (!isset($weatherData['main'])) {
            http_response_code(404);
            echo json_encode(["error" => "Localização não encontrada"]);
            exit;
        }
        echo json_encode([
            "city"                => $weatherData['name'],
            "temperature"         => $weatherData['main']['temp'],
            "feels_like"          => $weatherData['main']['feels_like'],
            "humidity"            => $weatherData['main']['humidity'],
            "wind_speed"          => $weatherData['wind']['speed'],
            "cloudiness"          => $weatherData['clouds']['all'],
            "weather_description" => $weatherData['weather'][0]['description']
        ]);
        break;

    case 'city':
        $city = urlencode(trim(urldecode($_GET['city'])));
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=pt";
        $weatherData = fetchWeatherData($url);

        if (!isset($weatherData['main'])) {
            http_response_code(404);
            echo json_encode(["error" => "Cidade não encontrada"]);
            exit;
        }
        saveSearchHistory($pdo, $weatherData);
        echo json_encode([
            "city"                => $weatherData['name'],
            "temperature"         => $weatherData['main']['temp'],
            "feels_like"          => $weatherData['main']['feels_like'],
            "humidity"            => $weatherData['main']['humidity'],
            "wind_speed"          => $weatherData['wind']['speed'],
            "cloudiness"          => $weatherData['clouds']['all'],
            "weather_description" => $weatherData['weather'][0]['description']
        ]);
        break;

    case 'history':
        $stmt = $pdo->query("SELECT * FROM weather_searches ORDER BY created_at DESC LIMIT 10");
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($history);
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Rota inválida ou parâmetro faltando"]);
        break;
}

exit;