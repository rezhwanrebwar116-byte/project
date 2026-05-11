<?php
session_start();
include "config.php";

if (isset($_POST['send'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $pass  = $_POST['pass'];

    // ADMIN LOGIN
    if ($email === "admin@gmail.com" && $pass === "admin") {
        $_SESSION['user'] = "Admin";
        $_SESSION['user_id'] = 0;
        $_SESSION['email'] = $email; 
        header("Location: admin.php");
        exit;
    }

    // USER LOGIN
    $query = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_assoc($query);
//$row['password'] = hashed password from database.
        if (password_verify($pass, $row['password'])) {

            $_SESSION['user']    = $row['name'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email']   = $row['email']; 

            header("Location: car.php");
            exit;

        } else {
            header("Location: login.php?error=wrongpass");
            exit;
        }

    } else {
        header("Location: createcc.php?error=noaccount");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stayle.css">
    <title>LogIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-80">

    <h1 class="text-2xl font-bold text-center mb-6 text-gray-700">Login</h1>

    <!--  Wrong Password Error -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'wrongpass'): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm text-center">
         Wrong password. Please try again.
    </div>
    <?php endif; ?>

    <!--  No Account Error -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'noaccount'): ?>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-2 rounded-lg mb-4 text-sm text-center">
         No account found. Please create one.
    </div>
    <?php endif; ?>

    <form action="login.php" method="post" class="space-y-4">

        <div>
            <label class="block text-gray-600">Email</label>
            <input 
                type="email" 
                name="email" 
                required 
                placeholder="Enter Email"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
        </div>

        <div>
            <label class="block text-gray-600">Password</label>
            <input 
                type="password" 
                name="pass" 
                required 
                placeholder="Enter Password"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
        </div>

        <button 
            type="submit"
            name="send"
            class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"
        >
            Log In
        </button>

        <div class="text-center mt-3">
            <a href="createcc.php" class="text-blue-500 hover:underline">
                Create Account
            </a>
        </div>

    </form>

</div>

</body>
</html>