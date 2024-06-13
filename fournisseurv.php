<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nomcommercial = $_POST["nomcommercial"];
  $siege_social = $_POST["siege_social"];
  $email = $_POST["email"];
  $activite = $_POST["nomActivite"];
  try {
    require_once "includes/dbh.inc.php";
    require "includes/fournisseurcontr.php";
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
      header("Location: fournisseur.php");
    } else {
      // vient de signupv.php
      if (isset($_SESSION["utilisateur"])) {
        $usernameF = $_SESSION["utilisateur"];
      }


      // Insertion dans le client
      $query0 = "UPDATE client 
      SET nomCommercialC = :nomCommercialC, 
          siegeSocialC = :siegeSocialC, 
          emailC = :emailC, 
          nomActiviteC = :nomActiviteC 
      WHERE username = :usernameC";


      $stmt0 = $pdo->prepare($query0);

      $stmt0->bindParam(":nomCommercialC", $nomcommercial);
      $stmt0->bindParam(":siegeSocialC", $siege_social);
      $stmt0->bindParam(":emailC", $email);
      $stmt0->bindParam(":nomActiviteC", $activite);
      $stmt0->bindParam(":usernameC", $usernameF);


      $stmt0->execute();

      // Liaisin dans le fournisseur usename forkey

      $query1 = "INSERT INTO fournisseur(usernameF) VALUES (:usernameF)";

      $stmt1 = $pdo->prepare($query1);

      $stmt1->bindParam(":usernameF", $usernameF);

      $stmt1->execute();

      // chercher lid pour linsrer dans le produit

      $query2 = "SELECT idFournisseur FROM fournisseur WHERE usernameF = :username;";
      $stmt2 = $pdo->prepare($query2);
      $stmt2->bindParam(":username", $usernameF);
      $stmt2->execute();
      $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
      $idFournisseur = $result2['idFournisseur'];

      // Insertion dans produit
      $products = $_POST["P"];
      $designations = $_POST["D"];
      $pux = $_POST["PU"];

      for ($i = 0; $i < count($products); $i++) {
        $product = $products[$i];
        $designation = $designations[$i];
        $pu = $pux[$i];
        $query3 = "INSERT INTO produit(idFournisseur, nomProduit, designationProduit, prixUnitaire) VALUES (:idFournisseur, :product, :dp, :pu);";
        $stmt3 = $pdo->prepare($query3);
        $stmt3->bindParam(":idFournisseur", $idFournisseur);
        $stmt3->bindParam(":product", $product);
        $stmt3->bindParam(":dp", $designation);
        $stmt3->bindParam(":pu", $pu);

        $stmt3->execute();
      }

      $pdo = null;
      $stmt0 = null;
      $stmt1 = null;
      $stmt2 = null;
      $stmt3 = null;

      header("Location: interfacefournisseur/dashboard.php");
      die();
    }
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
