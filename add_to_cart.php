<?php
session_start();
include 'includes/db_connect.php';
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Something went wrong.'];

// Check if ID exists
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  // Get food details
  $sql = "SELECT * FROM food_items WHERE id = $id LIMIT 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $food = $result->fetch_assoc();

    if (isset($_SESSION['cart'])) {
      $cart = $_SESSION['cart'];
    } else {
      $cart = [];
    }

    if (isset($cart[$id])) {
      $cart[$id]['quantity'] += 1;
    } else {
      $cart[$id] = [
        "id" => $food['id'],
        "name" => $food['name'],
        "price" => $food['price'],
        "quantity" => 1
      ];
    }

    $_SESSION['cart'] = $cart;

    $response = ['status' => 'success', 'message' => $food['name'] . ' added to cart!'];
  } else {
    $response = ['status' => 'error', 'message' => 'Item not found.'];
  }
} else {
  $response = ['status' => 'error', 'message' => 'No ID sent.'];
}

echo json_encode($response);
exit();
