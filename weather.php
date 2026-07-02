<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$apiKey = "d24032ecb2544930b30102822262706";
$city = "Delhi";

$url = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$city";

$response = file_get_contents($url);

$weather = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weather</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

<h2>🌤 Current Weather</h2>

<p><strong>City:</strong> <?php echo $weather['location']['name']; ?></p>

<p><strong>Temperature:</strong> <?php echo $weather['current']['temp_c']; ?> °C</p>

<p><strong>Condition:</strong> <?php echo $weather['current']['condition']['text']; ?></p>

<div style="text-align:center;">
    <img src="https:<?php echo $weather['current']['condition']['icon']; ?>">
</div>

<br>

<a href="dashboard.php">
    <button>⬅ Back to Dashboard</button>
</a>

</div>

</body>
</html>