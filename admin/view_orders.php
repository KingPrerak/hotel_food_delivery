<?php
session_start();
include '../includes/db_connect.php';

// ✅ Check admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// ✅ Mark status
if (isset($_GET['mark']) && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $newStatus = ($_GET['mark'] === 'completed') ? 'completed' : 'pending';
  $conn->query("UPDATE orders SET status='$newStatus' WHERE id=$id");
}

// ✅ Delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $conn->query("DELETE FROM orders WHERE id=$id");
}

// ✅ Fetch orders + user name
$sql = "
  SELECT orders.*, users.name AS customer_name 
  FROM orders
  JOIN users ON orders.user_id = users.id
  ORDER BY orders.created_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - View Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Admin Dashboard</a>
    <div class="collapse navbar-collapse">
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

<div class="container">
  <h2>All Orders</h2>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Address</th>
        <th>Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['customer_name']) ?></td>
          <td><?= htmlspecialchars($order['address']) ?></td>

          <!-- ✅ Correct way: Fetch order items for each order -->
          <td>
            <ul class="mb-0">
              <?php
              $orderId = intval($order['id']);
              $itemsSql = "
                SELECT oi.*, f.name 
                FROM order_items oi 
                JOIN food_items f ON oi.food_id = f.id 
                WHERE oi.order_id = $orderId
              ";
              $itemsResult = $conn->query($itemsSql);
              while ($item = $itemsResult->fetch_assoc()):
              ?>
                <li><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?> (₹<?= $item['price'] ?> each)</li>
              <?php endwhile; ?>
            </ul>
          </td>

          <td>Rs. <?= $order['total'] ?></td>
          <td>
            <?php if ($order['status'] == 'pending'): ?>
              <span class="badge bg-warning text-dark">Pending</span>
            <?php else: ?>
              <span class="badge bg-success">Completed</span>
            <?php endif; ?>
          </td>
          <td><?= $order['created_at'] ?></td>
          <td>
            <?php if ($order['status'] == 'pending'): ?>
              <a href="?mark=completed&id=<?= $order['id'] ?>" class="btn btn-sm btn-success">Mark Completed</a>
            <?php else: ?>
              <a href="?mark=pending&id=<?= $order['id'] ?>" class="btn btn-sm btn-warning">Mark Pending</a>
            <?php endif; ?>
            <a href="?delete=1&id=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
