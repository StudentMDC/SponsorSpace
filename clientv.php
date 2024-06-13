<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nomcommercial = $_POST["nomcommercial"];
  $siege_social = $_POST["siege_social"];
  $email = $_POST["email"];
  $activite = $_POST["nomActivite"];
  try {
    require_once "includes/dbh.inc.php";
    require "includes/clientcontr.php";
    $erros = [];
    // gerer les erreurs
    if (is_Empty($nomcommercial, $siege_social, $email, $activite)) {
      $erros["empty_input"] = "Veuillez remplir tous les champs! ";
    }
    if (is_Email_Invalid($email)) {
      $erros["invalid_email"] = "Utiliser un email valid! ";
    }

    // true if there is a data
    if ($erros) {
      $_SESSION["erros_signup"] = $erros;
      header("Location: client.php");
    } else {
      // vient de signupv.php
      if ($_SESSION["utilisateur"]) {
        $usernameC = $_SESSION["utilisateur"];
      }
      $query = "UPDATE client 
      SET nomCommercialC = :nomCommercialC, 
          siegeSocialC = :siegeSocialC, 
          emailC = :emailC, 
          nomActiviteC = :nomActiviteC 
      WHERE username = :usernameC";


      $stmt = $pdo->prepare($query);

      $stmt->bindParam(":usernameC", $usernameC);
      $stmt->bindParam(":nomCommercialC", $nomcommercial);
      $stmt->bindParam(":siegeSocialC", $siege_social);
      $stmt->bindParam(":emailC", $email);
      $stmt->bindParam(":nomActiviteC", $activite);

      $stmt->execute();

      // FREE UP RESOURCES BY CLOSING CONNECTION & STATEMENT
      $pdo = null;
      $stmt = null;


      header("Location: interfaceclient/dashboard.php");
      die();
    }
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
