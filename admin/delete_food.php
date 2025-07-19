<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

$id = intval($_GET['id']);

// Optionally delete image too
$result = $conn->query("SELECT image FROM food_items WHERE id = $id");
if ($row = $result->fetch_assoc()) {
  $image_path = '../assets/images/' . $row['image'];
  if (file_exists($image_path)) {
    unlink($image_path); // remove file
  }
}

$conn->query("DELETE FROM food_items WHERE id = $id");

header("Location: view_food.php");
exit();
