<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #001F3F; /* navy blue */
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background-color: #ffffff10;
      backdrop-filter: blur(8px);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      width: 350px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #FFD700; /* shiny gold */
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      outline: none;
    }

    button {
      background-color: #FFD700; /* gold */
      color: #001F3F; /* navy */
      border: none;
      padding: 10px 20px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    button:hover {
      background-color: #E6BE8A; /* lighter gold */
    }

    .login-link {
      margin-top: 15px;
      display: block;
      color: #FFD700;
      text-decoration: none;
    }

    .login-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Create Account</h2>
    <form action="index.php" method="post">
      <input type="text" placeholder="Full Name" required>
      <input type="email" placeholder="Email" required>
      <input type="password" placeholder="Password" required>
      <button type="submit">Create Account</button>
    </form>
    <a href="index.php" class="login-link">Already have an account? Log in</a>
  </div>
<?php
if (isset($_POST['register'])) {
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

  $sql = "INSERT INTO credentials (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
  
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Account created successfully!'); window.location='home.php';</script>";
  } else {
    echo "<script>alert('Error: " . $conn->error . "');</script>";
  }
}?>
</body>
</html>
