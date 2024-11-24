<?php
require "BackEnd/function.php";
require_once "BackEnd/helper.php";
session_start();

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']); // Ambil input dari form
  $password = md5($_POST['password']); // Enkripsi password

  $query = mysqli_query($conn, "
      SELECT user.id, user.username, role.role_name 
      FROM user 
      JOIN role ON user.role_id = role.id 
      WHERE user.username='$username' AND user.password='$password'
  ");

  if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $_SESSION['id'] = $row['id'];
    $_SESSION['role'] = $row['role_name'];

    // Redirect berdasarkan role
    if ($row['role_name'] == 'admin') {
      header("Location: admin/index");
    } elseif ($row['role_name'] == 'user') {
      header("Location: user/index");
    }
    exit();
  } else {
    $error = "Username atau password salah!";
  }
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LOGIN INVENTORY | ELASTOMIX</title>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .background {
      /* background-image: url('assets/img/Elastomix-Indonesia-28Feb.jpg'); */
      background-color: #45a049;
      background-size: cover;
      background-position: center;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1), 0 0 5px rgba(0, 0, 0, 0.2);
      width: 300px;
      border: 1px solid black;
    }

    h2 {
      text-align: center;
      margin-top: 50;

    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input {
      width: calc(100% - 20px);
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 10px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
  <div class="background">
    <div class="login-container">
      <h2>Form Login</h2>
      <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?= $error; ?></p>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <input type="text" id="username" name="username" placeholder="Masukkan username " required>
        </div>
        <div class="form-group">
          <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
        </div>
        <button type="submit" name="login">Login</button>
      </form>
    </div>
  </div>
</body>
</html>