<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side Validation
    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        try {

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['role'] = $row['role'];

                    header("Location: dashboard.php");
                    exit();

                } else {

                    $error = "Invalid email or password.";
                }

            } else {

                $error = "Invalid email or password.";
            }

            $stmt->close();

        } catch (Exception $e) {

            $error = "Something went wrong. Please try again later.";

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="js/validation.js"></script>

</head>

<body>

<div class="container">

<h2>User Login</h2>

<?php
if (!empty($error)) {
?>
<div class="error">
    <?php echo $error; ?>
</div>
<?php
}
?>

<form method="POST" name="loginForm" onsubmit="return validateLogin();">

    <label>Email</label>

    <input
        type="email"
        name="email"
        placeholder="Enter Email"
        required>

    <label>Password</label>

    <input
        type="password"
        name="password"
        placeholder="Enter Password"
        required>

    <input
        type="submit"
        name="login"
        value="Login">

</form>

</div>

<script src="js/validation.js"></script>

</body>
</html>