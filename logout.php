<?php
session_destroy();
header("Location: signup.php");
die();
  // if (isset($_SESSION["utilisateur"])) {
  //   $errors = $_SESSION['erros_signup'];
  //   echo "<br>";
  //   foreach ($errors as $error) {
  //     echo '<p class="c-red">' . $error . '</p>';
  //   }
  //   unset($_SESSION['erros_signup']);
  // }