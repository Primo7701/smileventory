<?php include 'dbcon.php'; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Smileventory Dashboard</title>

<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #405ba9;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    padding-top: 80px;
    position: relative;
    overflow-x: hidden;
  }

  body::before {
    content: "";
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: url('img/smilebox.png') no-repeat center center;
    background-size: cover;
    opacity: 0.3;
    z-index: -1;
  }

  nav {
    background-color: navy;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    position: fixed;
    top: 0; left: 0;
    width: 96%;
    z-index: 1000;
  }

  .nav-links a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 8px 14px;
    border-radius: 5px;
    transition: 0.3s;
  }

  .nav-links a:hover, .active-tab {
    background-color: #001f4d;
  }

  .container {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 1100px;
    margin: auto;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
  }

  .table-container {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  table {
    width: 100%; border-collapse: collapse;
  }

  th {
    background-color: navy;
    color: white;
    position: sticky;
    top: 0;
  }

  th, td {
    text-align: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
  }

  .btn {
    padding: 12px 15px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
  }

  .btn-add { background-color: navy; color: white; }
  .btn-buy { background-color: #017efc; color: white; }
  .btn-delete { background-color: crimson; color: white; }
  .btn-update { background-color: orange; color: white; }

  .popup {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 2000;
  }

  .popup-content {
    background: white;
    padding: 25px;
    border-radius: 12px;
    width: 380px;
    text-align: center;
  }

  .popup-input {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: 2px solid #ccc;
    border-radius: 8px;
  }

  .popup-btn {
    padding: 12px 20px;
    background-color: navy;
    color: white;
    border: none;
    border-radius: 8px;
    width: 45%;
    cursor: pointer;
    font-weight: bold;
  }

  .popup-close {
    padding: 12px 20px;
    background-color: crimson;
    color: white;
    border: none;
    border-radius: 8px;
    width: 45%;
    cursor: pointer;
    font-weight: bold;
  }
</style>
</head>

<body>

<nav>
  <div class="nav-logo">
    <img src="img/ICON 1.png" width="40" height="40">
    Smileventory
  </div>

  <div class="nav-links">
    <a href="#" class="active-tab">Inventory</a>
    <a href="sales_report.php">Sales Report</a>
    <a href="#" onclick="confirmLogout()">Logout</a>
    
  </div>
</nav>

<!-- INVENTORY -->
<div class="container">
  <h2>Inventory Dashboard</h2>

  <form method="GET" class="search-bar" style="text-align:right;">
    <input type="text" name="search" placeholder="Search product..." style="padding:12px;border-radius:5px;"
           value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit" class="btn">Search</button>
  </form>

  <button class="btn-add" onclick="openAddPopup()" style="padding:12px;border-radius:5px;">+ Add Product</button>

  <div class="table-container">
    <table>
      <tr>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Action</th>
      </tr>

      <?php
      /* ADD PRODUCT */
      if (isset($_POST['add_product'])) {
        $name = $_POST['product_name'];
        $qty = $_POST['quantity'];
        $price = $_POST['price'];
        $conn->query("INSERT INTO smileventory (product_name, quantity, price) VALUES ('$name', '$qty', '$price')");
        echo "<script>alert('Product Added!');window.location='dashboard.php';</script>";
      }

      /* UPDATE PRODUCT */
      if (isset($_POST['update_product'])) {
        $id = $_POST['product_id'];
        $name = $_POST['product_name'];
        $qty = $_POST['quantity'];
        $price = $_POST['price'];
        $conn->query("UPDATE smileventory SET product_name='$name', quantity='$qty', price='$price' WHERE id='$id'");
        echo "<script>alert('Product Updated!');window.location='dashboard.php';</script>";
      }

      /* BUY */
      if (isset($_GET['buy'])) {
    $id = $_GET['buy'];

    // Get product info
    $product = $conn->query("SELECT * FROM smileventory WHERE id='$id'")->fetch_assoc();
    $productName = $product['product_name'];
    $price = $product['price'];

    // Update quantity
    $conn->query("UPDATE smileventory SET quantity = quantity - 1 WHERE id='$id' AND quantity>0");

    // Log to sales table
    $conn->query("INSERT INTO sales_report (product_name, price) VALUES ('$productName', '$price')");

    echo "<script>alert('Product Bought & Recorded!');window.location='dashboard.php';</script>";
}


      /* DELETE */
      if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM smileventory WHERE id='$id'");
        echo "<script>alert('Product Deleted!');window.location='dashboard.php';</script>";
      }

      /* DISPLAY PRODUCTS — ALPHABETICAL ORDER */
      $search = isset($_GET['search']) ? $_GET['search'] : '';
      $result = $conn->query("SELECT * FROM smileventory 
                              WHERE product_name LIKE '%$search%' 
                              ORDER BY product_name ASC");

      while ($row = $result->fetch_assoc()) {
        echo "
          <tr>
            <td>{$row['product_name']}</td>
            <td>{$row['quantity']}</td>
            <td>₱" . number_format($row['price'],2) . "</td>
            <td>
              <a href='?buy={$row['id']}' class='btn btn-buy'>Out</a>
              <button class='btn btn-update' onclick=\"openUpdatePopup({$row['id']},'{$row['product_name']}',{$row['quantity']},{$row['price']})\">Update</button>
              <a href='?delete={$row['id']}' class='btn btn-delete'>Delete</a>
            </td>
          </tr>
        ";
      }
      ?>
    </table>
  </div>
</div>

<!-- ADD PRODUCT POPUP -->
<div class="popup" id="addPopup">
  <div class="popup-content">
    <h3>Add Product</h3>

    <form method="POST">
      <input type="text" class="popup-input" name="product_name" placeholder="Product Name" required>
      <input type="number" class="popup-input" name="quantity" placeholder="Quantity" required>
      <input type="number" class="popup-input" name="price" placeholder="Price (₱)" step="0.01" required>

      <button type="submit" name="add_product" class="popup-btn">Add</button>
      <button type="button" class="popup-close" onclick="closeAddPopup()">Cancel</button>
    </form>
  </div>
</div>

<!-- UPDATE PRODUCT POPUP -->
<div class="popup" id="updatePopup">
  <div class="popup-content">
    <h3>Update Product</h3>

    <form method="POST">
      <input type="hidden" name="product_id" id="update_id">

      <input type="text" class="popup-input" id="update_name" name="product_name" required>
      <input type="number" class="popup-input" id="update_quantity" name="quantity" required>
      <input type="number" class="popup-input" id="update_price" name="price" step="0.01" required>

      <button type="submit" name="update_product" class="popup-btn">Save</button>
      <button type="button" class="popup-close" onclick="closeUpdatePopup()">Cancel</button>
    </form>
  </div>
</div>

<script>
function openAddPopup() {
  document.getElementById('addPopup').style.display = 'flex';
}
function closeAddPopup() {
  document.getElementById('addPopup').style.display = 'none';
}

function openUpdatePopup(id, name, qty, price) {
  document.getElementById('updatePopup').style.display = 'flex';
  document.getElementById('update_id').value = id;
  document.getElementById('update_name').value = name;
  document.getElementById('update_quantity').value = qty;
  document.getElementById('update_price').value = price;
}
function closeUpdatePopup() {
  document.getElementById('updatePopup').style.display = 'none';
}

function confirmLogout() {
  if (confirm("Logout now?")) {
    window.location.href = 'logout.php';
  }
}
</script>

</body>
</html>
