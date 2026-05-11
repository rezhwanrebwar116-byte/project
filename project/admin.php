<?php
include "config.php";

$search = "";

// Base SQL query
$sql = "SELECT 
            users.name AS user_name, 
            users.email, 
            cars.name AS car_name, 
            cars.model, 
            cars.price,
            orders.id AS order_id
        FROM orders
        JOIN users ON orders.user_id = users.id
        JOIN cars ON orders.car_id = cars.id";


if (isset($_GET['send'])) {

    $search = trim($_GET['searchName']);

    if (!empty($search)) {

        $search = mysqli_real_escape_string($con, $search);

        $sql .= " WHERE users.name LIKE '%$search%'";
    }
}

// Execute query
$query = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- NAVBAR -->
<nav class="bg-white shadow-md">

    <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-gray-700">
            Admin Panel
        </h2>

        <!-- LINKS -->
        <div class="flex flex-col md:flex-row gap-3 md:gap-6">

            <a href="insert.php"
               class="text-gray-600 hover:text-blue-600 font-medium transition">
                Insert Page
            </a>

            <a href="car.php"
               class="text-gray-600 hover:text-blue-600 font-medium transition">
                Car Page
            </a>

        </div>

        <!-- SEARCH FORM -->
        <form method="GET" class="flex w-full md:w-auto">

            <input
                type="search"
                name="searchName"
                value="<?php echo htmlspecialchars($search); ?>"
                placeholder="Search by user name..."
                class="w-full md:w-64 px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            <button
                type="submit"
                name="send"
                class="bg-blue-600 text-white px-4 rounded-r-lg hover:bg-blue-700 transition"
            >
                Search
            </button>

        </form>

    </div>

</nav>

<!-- CONTENT -->
<div class="max-w-7xl mx-auto p-4 md:p-6">

    <div class="bg-white shadow-lg rounded-xl p-4 md:p-6">

        <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-700">
            Orders Table
        </h2>

        <div class="overflow-x-auto">

            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">

                <!-- TABLE HEAD -->
                <thead>

                    <tr class="bg-blue-600 text-white text-left">

                        <th class="p-3">User Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Car Name</th>
                        <th class="p-3">Model</th>
                        <th class="p-3">Price</th>

                    </tr>

                </thead>

                <!-- TABLE BODY -->
                <tbody>

                <?php if (mysqli_num_rows($query) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- USER NAME -->
                            <td class="p-3 font-medium text-gray-700">
                                <?php echo htmlspecialchars($row['user_name']); ?>
                            </td>

                            <!-- EMAIL -->
                            <td class="p-3 text-gray-600">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>

                            <!-- CAR NAME -->
                            <td class="p-3 text-blue-600 font-semibold">
                                <?php echo htmlspecialchars($row['car_name']); ?>
                            </td>

                            <!-- MODEL -->
                            <td class="p-3 text-gray-700">
                                <?php echo htmlspecialchars($row['model']); ?>
                            </td>

                            <!-- PRICE -->
                            <td class="p-3 text-green-600 font-bold">
                                $<?php echo number_format($row['price'], 2); ?>
                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="5"
                            class="text-center p-5 text-gray-500">
                            No results found
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>