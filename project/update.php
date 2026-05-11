<?php
session_start();
include "config.php";


$id = "";
$name = $brand = $model = $price = $detels = $image = "";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $result = mysqli_query($con, "SELECT * FROM cars WHERE id='$id'");
    if ($row = mysqli_fetch_assoc($result)) {
        $name   = $row['name'];
        $brand  = $row['brand'];
        $model  = $row['model'];
        $price  = $row['price'];
        $detels = $row['detels'];
        $image  = $row['image'];
    }
}


if (isset($_POST['save'])) {

    $id     = intval($_POST['id']);
    $name   = mysqli_real_escape_string($con, $_POST['ncar']);
    $brand  = mysqli_real_escape_string($con, $_POST['bcar']);
    $model  = mysqli_real_escape_string($con, $_POST['modelcar']);
    $price  = floatval($_POST['pcar']);
    $detels = mysqli_real_escape_string($con, $_POST['detelscar']);

    $file = $_FILES['file'];
    $filename = $file['name'];

    $newName = $image; // default = old image

   
    if (!empty($filename)) {

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['png','jpg','jpeg','gif','svg'];

        if (in_array($ext, $allowed)) {

            $newName = rand() . "." . $ext;
            move_uploaded_file($file['tmp_name'], "upload/$newName");

        } else {
            echo "<script>alert('Invalid file type');</script>";
        }
    }

    
    if ($id) {
        $sql = "UPDATE cars SET 
                name='$name',
                brand='$brand',
                model='$model',
                price='$price',
                detels='$detels',
                image='$newName'
                WHERE id='$id'";
    } else {
        $sql = "INSERT INTO cars (name, brand, model, price, detels, image)
                VALUES ('$name','$brand','$model','$price','$detels','$newName')";
    }

    if (mysqli_query($con, $sql)) {
        header("Location: car.php");
        exit();
    } else {
        echo "Database error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-6">

<h1 class="text-3xl font-bold text-center mb-6">
<?php echo ($id ? "Update Car 🚗" : "Add Car 🚗"); ?>
</h1>

<form method="POST" enctype="multipart/form-data" class="space-y-5">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<!-- Name -->
<input type="text" name="ncar" value="<?php echo $name; ?>"
class="w-full p-2 border rounded" placeholder="Car Name" required>

<!-- Brand -->
<input type="text" name="bcar" value="<?php echo $brand; ?>"
class="w-full p-2 border rounded" placeholder="Brand" required>

<!-- Model -->
<input type="text" name="modelcar" value="<?php echo $model; ?>"
class="w-full p-2 border rounded" placeholder="Model" required>

<!-- Price -->
<input type="text" name="pcar" value="<?php echo $price; ?>"
class="w-full p-2 border rounded" placeholder="Price" required>

<!-- Details -->
<input type="text" name="detelscar" value="<?php echo $detels; ?>"
class="w-full p-2 border rounded" placeholder="Details" required>

<!-- IMAGE INPUT -->
<input type="file" name="file"
class="w-full p-2 border rounded bg-white cursor-pointer">

<!-- SHOW OLD IMAGE -->
<?php if (!empty($image) && file_exists("upload/" . $image)): ?>
    <img src="upload/<?php echo htmlspecialchars($image); ?>"
         class="w-32 mt-3 rounded border">
<?php else: ?>
    <p class="text-gray-400 text-sm mt-2">No image selected</p>
<?php endif; ?>

<!-- BUTTON -->
<button name="save"
class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 mt-4">
<?php echo ($id ? "Update Car" : "Save Car"); ?>
</button>

</form>

<a href="car.php" class="block text-center mt-4 text-gray-600">
← Back
</a>

</div>
</div>

</body>
</html>