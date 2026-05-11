<?php
session_start();
include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Insert Car</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-md d-flex justify-content-between align-items-center w-100">
    <h2 class="mb-0">Admin Page</h2>
    <div class="d-flex gap-3">
      <a href="admin.php" class="btn btn-outline-secondary btn-sm">Orders</a>
      <a href="car.php" class="btn btn-outline-secondary btn-sm">Car Page</a>
    </div>
  </div>
</nav>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-100 to-blue-100 p-4">
  <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-6">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Insert Car Form 🚗</h1>

   
    <form action="insert.php" method="POST" enctype="multipart/form-data" class="space-y-5">

      <div>
        <label class="block text-gray-700 font-medium">Car Name</label>
        <input type="text" name="ncar" required
          class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Brand</label>
        <input type="text" name="bcar" required
          class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Model</label>
        <input type="text" name="modelcar" required
          class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Price ($)</label>
       
        <input type="number" step="0.01" min="0" name="pcar" required placeholder="e.g. 15000.50"
          class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Car Details</label>
       
        <textarea name="detelscar"  rows="3" placeholder="Car details..."
          class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
      </div>

      <div>
        <label class="block text-gray-700 font-medium">Car Image</label>
        <input type="file" name="file" required accept=".png,.jpg,.jpeg,.gif,.svg"
          class="w-full mt-1 border rounded-lg p-2 bg-gray-50">
      </div>

      <div class="flex flex-col sm:flex-row gap-3 pt-4">
        <button name="submit" type="submit"
          class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold shadow-md transition">
          Insert Car
        </button>
        <a href="car.php"
          class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg font-semibold transition">
          Cancel
        </a>
      </div>

    </form>
  </div>
</div>

<?php
if (isset($_POST['submit'])) {

    $name   = mysqli_real_escape_string($con, $_POST['ncar']);
    $brand  = mysqli_real_escape_string($con, $_POST['bcar']);
    $model  = mysqli_real_escape_string($con, $_POST['modelcar']);
    $price  = floatval($_POST['pcar']);
    $detels = mysqli_real_escape_string($con, $_POST['detelscar']);

    $file      = $_FILES['file'];
    $filename  = $file['name'];
    $filetemp  = $file['tmp_name'];
    $fileError = $file['error'];  // FIX: typo was "fileErorr"
    $filesize  = $file['size'];

    $fileExe       = explode('.', $filename); //Splits filename
    $fileActualExt = strtolower(end($fileExe));  // FIX: typo was "fileActualEe"
    $fileAllowed   = ['png', 'jpg', 'jpeg', 'svg', 'gif'];

    if (in_array($fileActualExt, $fileAllowed)) {
        if ($fileError === 0) {
            if ($filesize < 1000000000) {

                // FIX: create upload folder if it does not exist
                if (!is_dir('upload')) {
                    mkdir('upload', 0755, true);
                }

                $fileNewName     = rand() . "." . $fileActualExt;
                $fileDestination = "upload/$fileNewName";
                move_uploaded_file($filetemp, $fileDestination);

                $insert = mysqli_query($con,
                    "INSERT INTO `cars` (`name`, `brand`, `model`, `price`, `image`, `detels`)
                     VALUES ('$name', '$brand', '$model', '$price', '$fileNewName', '$detels')"
                );

                if ($insert) {
                    echo "<script>alert('Car added successfully!'); window.location.href='car.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Database error: " . addslashes(mysqli_error($con)) . "');</script>";
                }

            } else {
                echo "<script>alert('File is too large!');</script>";
            }
        } else {
            echo "<script>alert('File upload error (code: $fileError)!');</script>";
        }
    } else {
        echo "<script>alert('File type not allowed! Allowed: png, jpg, jpeg, gif, svg');</script>";
    }
}
?>
</body>
</html>