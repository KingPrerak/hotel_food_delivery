<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

$result = $conn->query("
  SELECT food_items.*, categories.name AS category_name
  FROM food_items
  JOIN categories ON food_items.category_id = categories.id
");
?>

<h1>All Food Items</h1>

<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Price</th>
      <th>Category</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>Rs. <?= $row['price'] ?></td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td><img src="../assets/images/<?= $row['image'] ?>" width="80"></td>
        <td>
          <a href="edit_food.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="delete_food.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
