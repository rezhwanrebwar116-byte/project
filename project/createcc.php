<?php
session_start();
include "config.php";


if (isset($_POST['send'])) {

    $name  = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    
    $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "An account with this email already exists.";
    } else {
        $pass   = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $insert = mysqli_query($con,
            "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('$name', '$email', '$pass')"
        );

        if ($insert) {
            echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
            exit;
        } else {
            $error = "Database error: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="stayle.css">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center h-screen bg-gradient-to-r from-purple-100 to-pink-100">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-80">

    <h1 class="text-2xl font-bold text-center mb-6 text-purple-600">Create Account</h1>

    <?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm text-center">
        ❌ <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    
    <form action="createcc.php" method="post" class="space-y-4">

        <div>
            <label class="block text-gray-600">Name</label>
            <input type="text" name="name" required placeholder="Enter Name"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        </div>

        <div>
            <label class="block text-gray-600">Email</label>
            <input type="email" name="email" required placeholder="Enter Email"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        </div>

        <div>
            <label class="block text-gray-600">Password</label>
            <input type="password" name="pass" required placeholder="Enter Password" minlength="6"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
        </div>

        <button type="submit" name="send"
            class="w-full bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600 transition">
            Create Account
        </button>

        <div class="text-center mt-3">
            <a href="login.php" class="text-purple-500 hover:underline">Already have an account? Login</a>
        </div>

    </form>

</div>

</body>
</html>