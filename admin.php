
<?php
session_start();

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["user_role"] !== "admin"
) {
    http_response_code(403);
    exit("Access Denied: Admin privileges required.");
}

require_once "config/db.php";

$stmt = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $stmt->fetch()["total"];

$stmt = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $stmt->fetch()["total"];

$stmt = $conn->query("SELECT COUNT(*) AS total FROM orders");
$totalOrders = $stmt->fetch()["total"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sufi Sweets Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h1>Sufi Sweets Admin Dashboard</h1>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card p-3">
            <h4>Total Users</h4>
            <h2><?= $totalUsers ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h4>Total Products</h4>
            <h2><?= $totalProducts ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h4>Total Orders</h4>
            <h2><?= $totalOrders ?></h2>
        </div>
    </div>
</div>

<a href="logout.php" class="btn btn-danger mt-4">Logout</a>

</body>
</html>
