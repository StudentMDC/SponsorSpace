<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign UP</title>
  <!-- Main CSS file -->
  <link rel="stylesheet" href="css/framework.css" />
  <link rel="stylesheet" href="css/master.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.min.css" />
  <style>
    .page {
      text-align: center;
      min-height: 150vh;
    }

    form label {
      display: block;
      text-align: left;
      margin-bottom: 15px;
      text-indent: 20px;
      letter-spacing: 1.5px;
    }

    select {
      width: 400px;
    }

    input[type="text"]:focus {
      width: 400px;
    }

    .container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      min-height: 70vh;
    }

    #new::before,
    #new::after {
      content: "";
      position: absolute;
      width: 0;
      height: 0;
    }
  </style>
</head>

<body>
  <div class="page">
    <!-- START Head -->
    <div class="container bg-white p-15 center-flex-c txt-c">
      <h1 id="new" class="c-bblue">Sign UP</h1>
      <form action="signupv.php" method="POST">

        <label for="username">Nom d'utilisateur</label>
        <input type="text" name="username" id="username" placeholder="Saisir un nom d'utilisateur" />

        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" placeholder="Saisir un mot de passe" />

        <?php
        if (isset($_SESSION["erros_signup"])) {
          $errors = $_SESSION["erros_signup"];
          echo "<br>";
          foreach ($errors as $error) {
            echo '<p class="c-red">' . $error . '</p>';
          }
          unset($_SESSION["erros_signup"]);
        }
        ?>

        <input class="bg-green w-full" type="submit" value="verifier" />
      </form>
      <p id="par">Avez vous déjà un compte ?</p>
      <div class="box">
        <div class="info">
          <a href="login.php">Log in</a>
          <i class="fas fa-long-arrow-alt-right"></i>
        </div>
      </div>

    </div>
  </div>
</body>

</html>