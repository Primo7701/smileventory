<?php include 'dbcon.php'; ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Report</title>

<style>
body {
  font-family: Arial;
  background: #f0f0f0;
  padding: 0;
  margin: 0;
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
    margin: 120px auto 40px auto; /* pushes container BELOW navbar */
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

table {
  width: 100%;
  border-collapse: collapse;
}
th {
  background: navy;
  color: white;
}
th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

.btn {
  background: navy;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.print-btn {
  background: green;
}

input[type="date"] {
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #999;
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
    <a href="dashboard.php">Inventory</a>
    <a href="sales_report.php" class="active-tab">Sales Report</a>
    <a href="#" onclick="confirmLogout()">Logout</a>
  </div>
</nav>

<!-- CONTAINER NOW BELOW NAVBAR -->
<div class="container">
<h2>Sales Report</h2>

<form method="GET">
  <label>From: </label>
  <input type="date" name="start" required>
  <label>To: </label>
  <input type="date" name="end" required>

  <button type="submit" class="btn">Filter</button>
  <button onclick="window.print()" type="button" class="btn print-btn">Print</button>
</form>

<br>

<table>
  <tr>
    <th>ID</th>
    <th>Product Name</th>
    <th>Price</th>
    <th>Date Sold</th>
  </tr>

<?php
if (isset($_GET['start']) && isset($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];

    $sql = $conn->query("SELECT * FROM sales 
                         WHERE DATE(sale_date) BETWEEN '$start' AND '$end'
                         ORDER BY sale_date ASC");

    $total = 0;

    while ($row = $sql->fetch_assoc()) {
        echo "
        <tr>
          <td>{$row['id']}</td>
          <td>{$row['product_name']}</td>
          <td>₱".number_format($row['price'], 2)."</td>
          <td>{$row['sale_date']}</td>
        </tr>";

        $total += $row['price'];
    }

    echo "
      <tr>
        <th colspan='2'>TOTAL SALES</th>
        <th colspan='2'>₱".number_format($total,2)."</th>
      </tr>
    ";
}
?>

</table>

</div>

<script>
function confirmLogout() {
  if (confirm("Logout now?")) {
    window.location.href = 'logout.php';
  }
}
</script>

</body>
</html>
