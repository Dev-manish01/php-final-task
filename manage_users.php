<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != "admin") {
    die("Access Denied. Admins only.");
}

try {
    $result = $conn->query("SELECT * FROM users");
} catch (Exception $e) {
    die("Unable to fetch users.");
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Manage Users</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="manage-page">

<div class="container" style="max-width:1100px;">

<h2>👥 User Management</h2>

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>

<td><?php echo htmlspecialchars($row['id']); ?></td>

<td><?php echo htmlspecialchars($row['name']); ?></td>

<td><?php echo htmlspecialchars($row['email']); ?></td>

<td><?php echo htmlspecialchars($row['phone']); ?></td>

<td><?php echo htmlspecialchars($row['address']); ?></td>

<td>
    <a href="edit_user.php?id=<?php echo $row['id']; ?>">✏ Edit</a>
    |
    <a href="delete_user.php?id=<?php echo $row['id']; ?>"
       onclick="return confirm('Are you sure you want to delete this user?');">
       🗑 Delete
    </a>
</td>

</tr>

<?php } ?>

</table>

<br><br>

<a href="dashboard.php">
    <button type="button">⬅ Back to Dashboard</button>
</a>

</div>

</body>
</html>