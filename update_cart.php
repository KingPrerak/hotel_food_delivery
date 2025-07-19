<?php
session_start();

if (isset($_GET['id']) && isset($_GET['action'])) {
  $id = intval($_GET['id']);
  $action = $_GET['action'];

  if (isset($_SESSION['cart'][$id])) {
    if ($action == "increase") {
      $_SESSION['cart'][$id]['quantity'] += 1;
    } elseif ($action == "decrease") {
      $_SESSION['cart'][$id]['quantity'] -= 1;
      if ($_SESSION['cart'][$id]['quantity'] <= 0) {
        unset($_SESSION['cart'][$id]);
      }
    }
  }
}

header("Location: cart.php");
exit();
