<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Example: using session cart
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  header("Location: cart.php");
  exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $address = trim($_POST['address']);
  $phone = trim($_POST['phone']);

  if (empty($address) || empty($phone)) {
    $errors[] = "All fields are required.";
  } else {
    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
      $total += $item['price'] * $item['quantity'];
    }

    // Insert order
    $sql = "INSERT INTO orders (user_id, total, address, phone) 
            VALUES ({$_SESSION['user_id']}, '$total', '$address', '$phone')";

    if ($conn->query($sql) === TRUE) {
      $order_id = $conn->insert_id;

      // Insert each item
      foreach ($cart as $item) {
        $food_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $conn->query("INSERT INTO order_items (order_id, food_id, quantity, price) 
                      VALUES ('$order_id', '$food_id', '$quantity', '$price')");
      }

      // Clear cart
      unset($_SESSION['cart']);

      header("Location: thank_you.php");
      exit();

    } else {
      $errors[] = "Order failed. Try again.";
    }
  }
}

include 'includes/header.php';
?>

<h2>Checkout</h2>

<?php
if (!empty($errors)) {
  foreach ($errors as $e) echo "<p class='text-danger'>$e</p>";
}
?>

<h4>Order Summary</h4>
<table class="table">
  <thead>
    <tr><th>Item</th><th>Quantity</th><th>Price</th></tr>
  </thead>
  <tbody>
    <?php
    $grandTotal = 0;
    foreach ($cart as $item):
      $itemTotal = $item['price'] * $item['quantity'];
      $grandTotal += $itemTotal;
    ?>
    <tr>
      <td><?php echo htmlspecialchars($item['name']); ?></td>
      <td><?php echo $item['quantity']; ?></td>
      <td>₹<?php echo number_format($itemTotal, 2); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="2"><strong>Total</strong></td>
      <td><strong>₹<?php echo number_format($grandTotal, 2); ?></strong></td>
    </tr>
  </tbody>
</table>

<form method="post">
  <div class="mb-3">
    <label>Delivery Address</label>
    <textarea name="address" class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <label>Phone Number</label>
    <input type="text" name="phone" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Place Order</button>
</form>

<?php include 'includes/footer.php'; ?>
