<?php
session_start();
include '../includes/db_connect.php';

// ✅ Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Stats:
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalFoods = $conn->query("SELECT COUNT(*) AS total FROM food_items")->fetch_assoc()['total'];
$totalOrdersCompleted = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'completed'")->fetch_assoc()['total'];
$totalOrdersPending = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="view_orders.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="add_food.php">Add Food</a></li>
        <li class="nav-item"><a class="nav-link" href="view_food.php">Manage Food</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Dashboard Content -->
<div class="container my-5">
  <h1 class="mb-4">Admin Dashboard</h1>

  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-bg-success h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Orders Completed</h5>
          <h2 class="card-text"><?php echo $totalOrdersCompleted; ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-danger h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Orders Pending</h5>
          <h2 class="card-text"><?php echo $totalOrdersPending; ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-primary h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Total Users</h5>
          <h2 class="card-text"><?php echo $totalUsers; ?></h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-info h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Total Food Items</h5>
          <h2 class="card-text"><?php echo $totalFoods; ?></h2>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-3">
    <a href="add_food.php" class="btn btn-success">Add New Food Item</a>
    <a href="view_food.php" class="btn btn-primary">Manage Food Items</a>
    <a href="view_orders.php" class="btn btn-warning">View Orders</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
