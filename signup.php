<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SignUp</title>
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
      <?php include "style.css" ?>
    </style>
  </head>

  <body>
    <!-- signup -->
    <section class="signup" id="signup">
      <h1>Gadget<span>AR</span></h1>
      <div class="container">
        <div class="bungkus">
        <div class="vektor">
          <img src="asset/img/daftar.png" alt="">
        </div>
        <div class="box">
          <div class="form-box">
          <?php
          include("php/config.php");
          if(isset($_POST['submit'])){
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            //verify email
            $verify_query = mysqli_query($con,"SELECT email FROM user WHERE email='$email'");
            
            if(mysqli_num_rows($verify_query) !=0 ){
              echo "<div class='message-error'>
                        <p>Email sudah digunakan, Coba yang lain!</p>
                    </div><br>";
              echo "<a href='javascript:self.history.back()'><button class='btn-kembali'>Kembali</button></a>";
            }else{
              $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
              mysqli_query($con,"INSERT INTO user(Email,Phone,Username,Password)VALUES('$email','$phone','$username','$hashedPassword')") or die("Error Occured");
              echo "<div class='message-sukses'>
                        <p>Daftar Berhasil!</p>
                    </div><br>";
              echo "<a href='login.php'><button class='btn'>Login</button></a>";
            }
          }else{
          ?>

              <h2>Daftar</h2>
            <form action="" method="post">
              <div class="form-input">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" maxlength="50" required />
              </div>
              <div class="form-input"> 
                <label for="phone">Nomor Ponsel</label>
                <input type="text" id="phone" name="phone" maxlength="13" required>
              </div>
              <div class="form-input">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" maxlength="25" required />
              </div>
              <div class="form-input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" maxlength="8" required />
              </div>
              <div class="button-submit">
                <input type="submit" class="btn" name="submit" value="Daftar" required />
              </div>
              <div class="link">
                Punya akun? <a href="login.php">Masuk</a>
              </div>
              </form>
            </div>
            <?php }?>
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
