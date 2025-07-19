<?php include 'includes/header.php'; ?>
<?php include 'includes/db_connect.php'; ?>
<!-- Hotel Banner -->
<div class="mb-4">
  <img src="assets/images/hotel_img.jpg" class="img-fluid w-100 banner-img" alt="Hotel Image">
</div>

<h1 class="mb-4">Our Menu</h1>

<?php
$cat_sql = "SELECT * FROM categories";
$cat_result = $conn->query($cat_sql);

if ($cat_result->num_rows > 0) {
  while ($cat = $cat_result->fetch_assoc()) {
    echo "<h3 class='mt-5'>" . $cat['name'] . "</h3>";
    echo "<div class='row'>";

    $food_sql = "SELECT * FROM food_items WHERE category_id = " . $cat['id'];
    $food_result = $conn->query($food_sql);

    if ($food_result->num_rows > 0) {
      while ($food = $food_result->fetch_assoc()) {
        ?>
        <div class="col-md-3 mb-4">
          <div class="card">
            <img src="assets/images/<?php echo $food['image']; ?>" 
     class="card-img-top food-img" 
     alt="<?php echo $food['name']; ?>">

            <div class="card-body">
              <h5 class="card-title"><?php echo $food['name']; ?></h5>
              <p class="card-text">Rs. <?php echo $food['price']; ?></p>
              <button class="btn btn-primary add-to-cart" data-id="<?php echo $food['id']; ?>">
  Add to Cart
</button>

            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<p>No items found in this category.</p>";
    }

    echo "</div>";
  }
} else {
  echo "<p>No categories found.</p>";
}

$conn->close();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
  $('.add-to-cart').click(function(e) {
    e.preventDefault();

    var foodId = $(this).data('id');

    $.ajax({
      url: 'add_to_cart.php',
      type: 'GET',
      data: { id: foodId },
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          alert(response.message);
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function() {
        alert('AJAX error!');
      }
    });
  });
});
</script>
<?php include 'includes/footer.php'; ?>
