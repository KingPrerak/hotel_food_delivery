<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

$id = intval($_GET['id']);
$success = '';
$error = '';

$result = $conn->query("SELECT * FROM food_items WHERE id = $id");
$food = $result->fetch_assoc();

// Get categories
$cat_result = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $cat_result->fetch_assoc()) {
  $categories[] = $row;
}

if (isset($_POST['update_food'])) {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $category_id = intval($_POST['category_id']);

  $update_img = '';
  if (!empty($_FILES['image']['name'])) {
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = '../assets/images/' . basename($image_name);
    if (move_uploaded_file($image_tmp, $image_path)) {
      $update_img = ", image = '$image_name'";
    } else {
      $error = "Image upload failed.";
    }
  }

  if (!$error) {
    $sql = "UPDATE food_items SET name='$name', price=$price, category_id=$category_id $update_img WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
      $success = "Food item updated.";
      header("Location: view_food.php");
      exit();
    } else {
      $error = "Failed to update.";
    }
  }
}

?>

<h1>Edit Food Item</h1>
<?php if ($success) echo "<p class='text-success'>$success</p>"; ?>
<?php if ($error) echo "<p class='text-danger'>$error</p>"; ?>

<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($food['name']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Price</label>
    <input type="number" step="0.01" name="price" class="form-control" value="<?= $food['price'] ?>" required>
  </div>

  <div class="mb-3">
    <label>Category</label>
    <select name="category_id" class="form-control">
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $food['category_id'] == $cat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label>Image</label><br>
    <img src="../assets/images/<?= $food['image'] ?>" width="120" class="mb-2">
    <input type="file" name="image" class="form-control">
  </div>

  <button type="submit" name="update_food" class="btn btn-primary">Update</button>
</form>
