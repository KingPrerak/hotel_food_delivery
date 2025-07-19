<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include 'includes/db_connect.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<h2>My Orders</h2>

<?php if ($result->num_rows > 0): ?>
  <?php while ($order = $result->fetch_assoc()): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5>Order #<?php echo $order['id']; ?> | ₹<?php echo $order['total']; ?></h5>
        <p>
          Status: 
          <?php if ($order['status'] == 'pending'): ?>
            <span class="badge bg-warning text-dark">Pending</span>
          <?php else: ?>
            <span class="badge bg-success">Completed</span>
          <?php endif; ?>
          | Placed on: <?php echo $order['created_at']; ?>
        </p>
        <h6>Items:</h6>
        <ul>
          <?php
          $items = $conn->query("
            SELECT oi.*, f.name 
            FROM order_items oi 
            JOIN food_items f ON oi.food_id = f.id 
            WHERE oi.order_id = {$order['id']}
          ");
          while ($item = $items->fetch_assoc()):
          ?>
            <li>
              <?php echo htmlspecialchars($item['name']); ?> 
              x <?php echo $item['quantity']; ?> 
              (₹<?php echo $item['price']; ?> each)
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>You have no orders yet.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
