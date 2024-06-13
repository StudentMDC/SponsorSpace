<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $password = $_POST["password"];
  $username = $_POST["username"];

  try {
    require_once "includes/dbh.inc.php";
    require "includes/singupcontr.php";
    $erros = [];
    // gerer les erreurs
    if (is_Empty($password, $username)) {
      $erros["empty_input"] = "Veuillez remplir tous les champs! ";
    }
    if (is_username_Taken($pdo, $username)) {
      $erros["username_taken"] = "Nom d'utilisateur existant! ";
    }

    // true if there is a data
    if ($erros) {
      $_SESSION["erros_signup"] = $erros;
      header("Location: signup.php");
    } else {
      // Premier fois in la photo par defaut
      $_SESSION["profil"] = 'avatar.png'; 
      $_SESSION["utilisateur"] = $username;
      $query = "INSERT INTO client (username, pwd, profilimgC) VALUES (:username, :pwd, :profilimgC);";

      $stmt = $pdo->prepare($query);

      $stmt->bindParam(":username", $username);
      $stmt->bindParam(":pwd", $password);
      $stmt->bindParam(":profilimgC", $_SESSION["profil"]);

      $stmt->execute();

      // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
      $pdo = null;
      $stmt = null;

      header("Location: acceuilaftersignup.html");
      die();
    }
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
