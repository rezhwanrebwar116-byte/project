<?php
session_start();
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Shop</title>

    
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="navbar bg-white shadow-sm border-bottom fixed-top py-3">

    <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">

        <!-- Welcome -->
        <h4 class="m-0 fw-bold text-dark">

            Welcome

            <span class="text-primary">
                <?php echo htmlspecialchars($_SESSION['user'] ?? 'Guest'); ?>
            </span>

        </h4>

        <!-- SEARCH FORM -->
        <form
            class="d-flex w-100 justify-content-center"
            style="max-width: 450px;"
            role="search"
            action="car.php"
            method="GET"
        >

            <input
                class="form-control me-2 rounded-pill shadow-sm"
                type="search"
                name="inputSerch"

                value="<?php echo htmlspecialchars($_GET['inputSerch'] ?? ''); ?>"

                placeholder="Search by brand..."
                aria-label="Search"
            >

            <button
                class="btn btn-primary rounded-pill px-4 shadow-sm"
                name="search"
                type="submit"
            >
                Search
            </button>

        </form>

        <!-- BUTTONS -->
        <div class="d-flex flex-wrap gap-2 justify-content-center">

            <?php if (isset($_SESSION['user'])): ?>

                <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com'): ?>

                    <a
                        href="admin.php"
                        class="btn btn-outline-primary rounded-pill"
                    >
                        Admin Panel
                    </a>

                    <a
                        href="insert.php"
                        class="btn btn-success rounded-pill"
                    >
                        + Add Car
                    </a>

                <?php endif; ?>

                <!-- Logout -->
                <a
                    href="logout.php"
                    class="btn btn-danger rounded-pill"
                >
                    Logout
                    (<?php echo htmlspecialchars($_SESSION['user']); ?>)
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="btn btn-primary rounded-pill"
                >
                    Login
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>

<div class="pt-32"></div>

<?php

// Default query
$query = "SELECT * FROM cars";

// Search only when button clicked
if (isset($_GET['search'])) {

    // Get search input
    $inputbrand = trim($_GET['inputSerch']);

    // If input not empty
    if (!empty($inputbrand)) {

        // Protect against SQL injection
        $inputbrand = mysqli_real_escape_string($con, $inputbrand);

        // Search query
        $query = "
            SELECT * FROM cars
            WHERE brand LIKE '%$inputbrand%'
            OR name LIKE '%$inputbrand%'
        ";
    }
}

// Execute query
$result = mysqli_query($con, $query);

?>

<!-- CAR GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">

<?php

if ($result && mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

?>

    <!-- CARD -->
    <div class="flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition">

        <!-- IMAGE -->
        <div class="h-48 w-full overflow-hidden bg-slate-100">

            <img
                src="upload/<?php echo htmlspecialchars($row['image']); ?>"
                alt="<?php echo htmlspecialchars($row['name']); ?>"
                class="h-full w-full object-cover"
                onerror="this.src='car.jpg'"
            >

        </div>

        <!-- CONTENT -->
        <div class="flex flex-1 flex-col p-5">

            <!-- BRAND -->
            <span class="text-xs font-bold uppercase text-blue-600">

                <?php echo htmlspecialchars($row['brand']); ?>

            </span>

            <!-- CAR NAME -->
            <h3 class="mt-1 text-lg font-bold text-slate-900">

                <?php echo htmlspecialchars($row['name']); ?>

            </h3>

            <!-- PRICE -->
            <div class="mt-2 text-sm text-slate-600">

                <p>
                    Price:
                    <span class="font-bold text-green-600">

                        $<?php echo number_format($row['price'], 2); ?>

                    </span>
                </p>

            </div>

            <!-- DETAILS BUTTON -->
            <a
                href="detels.php?id=<?php echo $row['id']; ?>"
                class="mt-4 w-full inline-block text-center rounded-lg bg-slate-800 py-2 text-white hover:bg-slate-700 transition"
            >
                View Details
            </a>

        </div>

    </div>

<?php

    }

} else {

    echo '

    <div class="col-span-3">

        <div class="text-center text-red-500 text-lg font-semibold mt-10">

            ❌ No cars found

        </div>

    </div>

    ';
}

?>

</div>

</body>
</html>