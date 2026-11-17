
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "config/db.php";

$stmt = $conn->query(
    "SELECT id, full_name, email, city, role
     FROM users ORDER BY id DESC"
);

$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2>Registered Customers</h2>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>City</th>
            <th>Role</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= (int)$u["id"] ?></td>
            <td><?= htmlspecialchars($u["full_name"]) ?></td>
            <td><?= htmlspecialchars($u["email"]) ?></td>
            <td><?= htmlspecialchars($u["city"]) ?></td>
            <td><?= htmlspecialchars($u["role"]) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
