<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $file_name = basename($_FILES['image']['name']);
  $idp = $_POST["idProduit"];

  try {
    require_once "../includes/dbh.inc.php";
    require "../includes/clientcontr.php";
      // vient de signupv.php
      if ($_SESSION["utilisateur"]) {
        $usernameC = $_SESSION["utilisateur"];
      }
      $queryp = "UPDATE produit
                  SET imgP=:imgP
                  WHERE idProduit=:idp;";
      $stmtp = $pdo->prepare($queryp);
      $stmtp->bindParam(":imgP", $file_name);
      $stmtp->bindParam(":idp", $idp);
      $stmtp->execute();
      $resultp = $stmtp->fetchAll(PDO::FETCH_ASSOC);

      // creer la session de produit a partir de la db
      $query1 = "SELECT imgP	
      FROM produit
      WHERE idProduit=:idp;";

      $stmt1 = $pdo->prepare($query1);

      $stmt1->bindParam(":idp", $idp);

      $stmt1->execute();

      $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
      if ($result1[0]["imgP"]=='') {
        $_SESSION["profilp"] = 'course-04.jpg';
      }
      else {
        $_SESSION["profilp"] = $result1[0]["imgP"]; 
      }

      // FREE UP RESOURCES BY CLOSING CONNECTION & STATEMENT
      $pdo = null;
      $stmtp = null;


      header("Location: mesproduits.php");
      die();
    
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
