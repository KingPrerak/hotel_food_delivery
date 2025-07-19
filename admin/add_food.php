<?php
session_start();
include '../includes/db_connect.php';

// Admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

$success = '';
$error = '';

if (isset($_POST['add_food'])) {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $category_id = intval($_POST['category_id']);

  $image_name = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];
  $image_path = '../assets/images/' . basename($image_name);

  if (move_uploaded_file($image_tmp, $image_path)) {
    $sql = "INSERT INTO food_items (name, price, image, category_id) VALUES ('$name', $price, '$image_name', $category_id)";
    if ($conn->query($sql) === TRUE) {
      $success = "Food item added successfully!";
    } else {
      $error = "Database error: " . $conn->error;
    }
  } else {
    $error = "Failed to upload image.";
  }
}

$categories = [];
$cat_result = $conn->query("SELECT * FROM categories");
while ($row = $cat_result->fetch_assoc()) {
  $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add New Food Item</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card shadow p-4">
        <h2 class="mb-4 text-center">Add New Food Item</h2>

        <?php if ($success): ?>
          <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter food name" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Price (Rs.)</label>
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
              <option value="">Select Category</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
          </div>

          <button type="submit" name="add_food" class="btn btn-primary w-100">Add Food Item</button>
        </form>

      </div>
    </div>
  </div>
</div>

</body>
</html>
