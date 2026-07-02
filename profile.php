<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$success = "";
$error = "";

if(isset($_POST['update'])){

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $gender = trim($_POST['gender']);

    $profile_image = $user['profile_image'];

    // Image Upload
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['name']!=""){

    $allowed = ["jpg","jpeg","png"];

    $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

    if(in_array($extension,$allowed)){

        $filename = time()."_".basename($_FILES['profile_image']['name']);

        $target = "uploads/".$filename;

        if(move_uploaded_file($_FILES['profile_image']['tmp_name'],$target)){

            $profile_image = $filename;

        }

    }else{

        $error = "Only JPG, JPEG and PNG files are allowed.";

    }

}

    $update = $conn->prepare("UPDATE users SET name=?, phone=?, city=?, gender=?, profile_image=? WHERE id=?");
    $update->bind_param("sssssi", $name, $phone, $city, $gender, $profile_image, $user_id);
    if($update->execute()){

        $_SESSION['name'] = $name;

        $success = "Profile updated successfully.";

        // Refresh user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

    }else{

        $error = "Failed to update profile.";

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>My Profile</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

<h2>My Profile</h2>

<?php
if($success!=""){
    echo "<div class='success'>$success</div>";
}

if($error!=""){
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST" enctype="multipart/form-data">

    <label>Profile Picture</label><br><br>

<?php if(!empty($user['profile_image'])){ ?>

    <img
    class="profile-image"
    src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>">

    <br><br>

<?php } ?>

    <input type="file" name="profile_image">

    <br><br>

    <label>Name</label>

    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($user['name']); ?>"
        required>

    <label>Email</label>

    <input
        type="email"
        name="email"
        value="<?php echo htmlspecialchars($user['email']); ?>"
        readonly>

    <label>Phone</label>

    <input
        type="text"
        name="phone"
        value="<?php echo htmlspecialchars($user['phone']); ?>">

        <label>City</label>

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

        <br><br>   

        <input
        type="submit"
        name="update"
        value="Update Profile">
    </form>

<br>

<a href="dashboard.php">← Back to Dashboard</a>


</div>

</body>
</html>