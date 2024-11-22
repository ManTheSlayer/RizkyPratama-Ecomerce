<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- css -->
    <!-- <link rel="stylesheet" href="style.css" /> -->
     <style>
      <?php include "../style.css" ?>
    </style>
  </head>

  <body>
    <!-- Login -->
    <section class="login" id="login">
      <h1>Gadget<span>AR</span></h1>
      <div class="container">
        <div class="bungkus">
        <div class="vektor">
          <img src="asset/img/login.png" alt="">
        </div>
        <div class="box">
          <div class="form-box">
            <?php
              include("../php/config.php");
              if (isset($_POST['submit'])) {
              $email = mysqli_real_escape_string($con, $_POST['email']);
              $password = mysqli_real_escape_string($con, $_POST['password']);

              // Memeriksa apakah email ada di database
              $result = mysqli_query($con, "SELECT * FROM `admin` WHERE email='$email'") or die("Select Error");
              $row = mysqli_fetch_assoc($result);

              if (is_array($row) && !empty($row)) {
                  // Memverifikasi password
                  if (md5($password) == $row['password']) {
                      $_SESSION['valid'] = $row['email'];
                      $_SESSION['username'] = $row['username'];
                      $_SESSION['id_admin'] = $row['id']; // Ubah variabel sesi menjadi id_user
                      echo "<script type='text/javascript'>
                              window.location.href = 'index.php';
                          </script>"; // Pindah ke halaman index setelah login berhasil
                      exit();
                  } else {
                      echo "<div class='message-error'>
                              <p>Password Salah!</p>
                          </div><br>";
                      echo "<a href='login.php'><button class='btn-kembali'>Kembali</button></a>";
                  }
              } else {
                  echo "<div class='message-error'>
                          <p>Email Salah!</p>
                      </div><br>";
                  echo "<a href='login.php'><button class='btn-kembali'>Kembali</button></a>";
              }
          } else {
          ?>

            <h2>Masuk Admin</h2>
            <form action="" method="post">
              <div class="form-input">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required />
              </div>
              <div class="form-input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required />
              </div>
              <div class="button-submit">
                <input type="submit" class="btn" name="submit" value="Masuk" required />
              </div>
            </form>
          </div>
          <?php } ?>
        </div>
        </div>
        </div>
      </div>
    </section>

    <!-- Feather Icons -->
    <script>
      feather.replace();
    </script>

    <!-- javaScript -->
    <script src="script.js"></script>
  </body>
</html>
