<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$apiKey = "d24032ecb2544930b30102822262706";
$city = "Delhi";

$url = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$city";

$response = @file_get_contents($url);

$weather = null;

if ($response !== false) {
    $weather = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-card">

        <h1>👋 Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

        <p class="role">
            Role:
            <strong><?php echo ucfirst($_SESSION['role']); ?></strong>
        </p>

        <?php if(isset($weather['location'])){ ?>

        <div class="weather-box">

            <h3>🌤 Current Weather</h3>

            <h4><?php echo htmlspecialchars($weather['location']['name']); ?></h4>

            <p>
                🌡 Temperature:
                <strong><?php echo $weather['current']['temp_c']; ?>°C</strong>
            </p>

            <p>
                ☁ Condition:
                <?php echo htmlspecialchars($weather['current']['condition']['text']); ?>
            </p>

            <img
            src="https:<?php echo $weather['current']['condition']['icon']; ?>"
            alt="Weather Icon">

        </div>

        <br>

        <?php } ?>

        <div class="dashboard-buttons">

            <a href="profile.php">
                <button>👤 My Profile</button>
            </a>

            <?php if($_SESSION['role']=="admin"){ ?>

            <a href="manage_users.php">
                <button>👥 Manage Users</button>
            </a>

            <?php } ?>

            <a href="weather.php">
                <button>🌤 Weather Details</button>
            </a>

            <a href="logout.php">
                <button class="logout-btn">🚪 Logout</button>
            </a>

        </div>

    </div>

</div>

</body>

</html>