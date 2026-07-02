<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

$success = "";
$error = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid User ID.");
}

$id = $_GET['id'];

try {

    // Fetch User
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("User not found.");
    }

    $user = $result->fetch_assoc();

    if (isset($_POST['update'])) {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $city = trim($_POST['city']);
        $gender = trim($_POST['gender']);

        // Server-side Validation
        if (empty($name) || empty($email)) {

            $error = "Name and Email are required.";

        } elseif (strlen($name) < 3) {

            $error = "Name must be at least 3 characters.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $error = "Please enter a valid email.";

        } else {

        $update = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, city=?, gender=? WHERE id=?");
        $update->bind_param("sssssi", $name, $email, $phone, $city, $gender, $id);

            if ($update->execute()) {

                $success = "User updated successfully.";

                // Refresh Data
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

            } else {

                $error = "Update failed.";

            }

            $update->close();

        }

    }

    $stmt->close();

} catch (Exception $e) {

    die($e->getMessage());

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit User</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

<h2>✏️ Edit User</h2>

<?php
if (!empty($success)) {
    echo "<div class='success'>$success</div>";
}

if (!empty($error)) {
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<label>Full Name</label>

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($user['name']); ?>"
required>

<label>Email Address</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($user['email']); ?>"
required>

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($user['phone']); ?>">

<label>City</label>

<input
type="text"
name="city"
value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">

<label>Gender</label>

<select name="gender">

<option value="Male" <?php if(($user['gender'] ?? '')=="Male") echo "selected"; ?>>Male</option>

<option value="Female" <?php if(($user['gender'] ?? '')=="Female") echo "selected"; ?>>Female</option>

<option value="Other" <?php if(($user['gender'] ?? '')=="Other") echo "selected"; ?>>Other</option>

</select>

<input
type="submit"
name="update"
value="💾 Update User">

</form>

<br>

<a href="manage_users.php">
    <button type="button">⬅ Back to Manage Users</button>
</a>

</div>

</body>
</html>