<?php
include "db.php";

$success = "";
$error = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side Validation
    if (empty($name) || empty($email) || empty($password)) {

        $error = "All fields are required.";

    } elseif (strlen($name) < 3) {

        $error = "Name must be at least 3 characters.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email.";

    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        try {

            // Check if email already exists
            $check = $conn->prepare("SELECT id FROM users WHERE email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {

                $error = "Email already registered.";

            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
                $stmt->bind_param("sss", $name, $email, $hashedPassword);

                if ($stmt->execute()) {

                    $success = "Registration Successful!";

                } else {

                    $error = "Registration Failed.";

                }

                $stmt->close();
            }

            $check->close();

        } catch (Exception $e) {

            $error = "Something went wrong. Please try again.";

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Register</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="js/validation.js"></script>

</head>

<body>

<div class="container">

<h2>User Registration</h2>

<?php
if (!empty($success)) {
    echo "<div class='success'>$success</div>";
}

if (!empty($error)) {
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST"
      name="registerForm"
      onsubmit="return validateRegister();">

    <label>Name</label>

    <input
        type="text"
        name="name"
        placeholder="Enter Name"
        required>

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
        name="register"
        value="Register">

</form>

<br>

<a href="login.php">Already have an account? Login</a>

</div>

</body>
</html>