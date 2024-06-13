<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $idp = $_POST["idProduit"];
  $nomp = $_POST["nomproduit"];
  $desp = $_POST["designationproduit"];
  $prixp = $_POST["prixproduit"];

  try {
    require_once "../includes/dbh.inc.php";
    require "../includes/clientcontr.php";
    $erros = [];
    // gerer les erreurs
    if (is_Empty2($nomp, $prixp)) {
      $erros["empty_input"] = "Veuillez remplir tous les champs! ";
    }

    // true if there is a data
    if ($erros) {
      $_SESSION["erros_signup"] = $erros;
      header("Location: updateprod.php");
    } else {


      $queryp = "UPDATE produit
          SET nomProduit=:nomProduit, prixUnitaire=:prixUnitaire, designationProduit	=:desProduit
          WHERE idProduit=:idp;";
      $stmtp = $pdo->prepare($queryp);
      $stmtp->bindParam(":nomProduit", $nomp);
      $stmtp->bindParam(":desProduit", $desp);
      $stmtp->bindParam(":prixUnitaire", $prixp);
      $stmtp->bindParam(":idp", $idp);
      $stmtp->execute();
      $resultp = $stmtp->fetchAll(PDO::FETCH_ASSOC);
      // FREE UP RESOURCES BY CLOSING CONNECTION & STATEMENT
      $pdo = null;
      $stmt = null;


      header("Location: mesproduits.php");
      die();
    }
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
