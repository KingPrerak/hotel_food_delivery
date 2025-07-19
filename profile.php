<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include 'includes/header.php';
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>

<p>This is your Dashboard.</p>

<ul>
  <li><a href="orders.php">View My Orders</a></li>
  <li><a href="logout.php" class="btn btn-danger mt-2">Logout</a></li>
</ul>

<?php include 'includes/footer.php'; ?>
