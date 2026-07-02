<?php

include "db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid User ID.");
}

$id = $_GET['id'];

try {

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: manage_users.php");
        exit();

    } else {

        echo "Unable to delete user.";

    }

    $stmt->close();

} catch (Exception $e) {

    echo "Something went wrong. Please try again.";

}

?>