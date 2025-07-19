<?php session_start(); ?>
<?php include 'includes/header.php'; ?>

<h1>Your Cart</h1>

<?php
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  $cart = $_SESSION['cart'];
  $total = 0;
?>

  <table class="table">
    <thead>
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
      ?>
        <tr>
          <td><?php echo htmlspecialchars($item['name']); ?></td>
          <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
          <td>
            <a href="update_cart.php?id=<?php echo $item['id']; ?>&action=decrease" class="btn btn-sm btn-secondary">-</a>
            <?php echo $item['quantity']; ?>
            <a href="update_cart.php?id=<?php echo $item['id']; ?>&action=increase" class="btn btn-sm btn-secondary">+</a>
          </td>
          <td>Rs. <?php echo number_format($subtotal, 2); ?></td>
          <td><a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">Remove</a></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <h4>Total: Rs. <?php echo number_format($total, 2); ?></h4>

  <!-- ✅ This is the fixed link -->
  <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>

<?php
} else {
  echo "<p>Your cart is empty.</p>";
}
?>

<?php include 'includes/footer.php'; ?>
