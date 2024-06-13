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
    // Verifier si l'utilisateur existe
    $query0 = "SELECT username,pwd
    FROM client
    WHERE username=:username AND pwd=:pwd;";
    $stmt0 = $pdo->prepare($query0);
    $stmt0->bindParam(":username", $username);
    $stmt0->bindParam(":pwd", $password);
    $stmt0->execute();
      
    $result0 = $stmt0->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result0)) {
      $erros["userdonotexist"] = "L'utilisateur n'existe pas! ";
    }
    // true if there is a data
    if ($erros) {
      $_SESSION["erros_signup"] = $erros;
      header("Location: login.php");
    } else {
      // chager le nom d'utilisateur
      $_SESSION["utilisateur"] = $username;

      $query = "SELECT usernameF
      FROM fournisseur
      WHERE usernameF=:username;";

      $stmt = $pdo->prepare($query);

      $stmt->bindParam(":username", $username);

      $stmt->execute();
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // chager le profil
      
      $query1 = "SELECT profilimgC
      FROM client
      WHERE username=:username;";

      $stmt1 = $pdo->prepare($query1);
      $stmt1->bindParam(":username", $username);

      $stmt1->execute();

      $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

      if ($result1[0]["profilimgC"]=='') {
        $_SESSION["profil"] = 'avatar.png';
      }
      else {
        $_SESSION["profil"] = $result1[0]["profilimgC"]; 
      }
  

      if (empty($result)) {
        header("Location: interfaceclient/dashboard.php");
      }
      else {
        header("Location: interfacefournisseur/dashboard.php");
      }
      // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
      $pdo = null;
      $stmt = null;

      
      die();
    }
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
