<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
try {
  
  require_once "../includes/dbh.inc.php";
  $idp = $_POST["idProduit"];

  $query = "SELECT * from activite;";

  $stmt = $pdo->prepare($query);

  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // value of form

  $queryp = "SELECT *
      FROM produit
      INNER JOIN fournisseur
      ON fournisseur.idFournisseur=produit.idFournisseur
      WHERE fournisseur.usernameF=:username AND produit.idProduit=:idp;";
  $stmtp = $pdo->prepare($queryp);
  $stmtp->bindParam(":username", $utilisateur);
  $stmtp->bindParam(":idp", $idp);
  $stmtp->execute();
  $resultp = $stmtp->fetchAll(PDO::FETCH_ASSOC);

  // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
  $pdo = null;
  $stmtU = null;
  $stmt = null;
} catch (PDOException $e) {
  die("Connexion échouée: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modifier Produit</title>
  <!-- Render all elms normally -->
  <!-- <link rel="stylesheet" href="css/normalise.css" /> -->
  <!-- Main CSS file -->
  <link rel="stylesheet" href="../css/framework.css" />
  <link rel="stylesheet" href="../css/master.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/all.min.css" />
  <style>
    .page {
      text-align: center;
      min-height: 100vh;
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
      transform: translate(-50%,-50%);
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
      <h1 id="new" class="c-bblue">Modifier Produit</h1>

      <form action="updateprodv.php" method="POST">
        <input type="hidden" name="idProduit" value="<?php echo $idp?>">

        <label for="nomcommercial">Nom produit</label>
        <input type="text" name="nomproduit" placeholder="Saisir le nom produit" value="<?php echo $resultp[0]["nomProduit"]?>"/>

        <label for="nomcommercial">Designation produit</label>
        <input type="text" name="designationproduit" placeholder="Saisir la désignation produit" value="<?php echo $resultp[0]["designationProduit"]?>"/>

        <label for="email">Prix produit</label>
        <input type="number" class="nprd" name="prixproduit" placeholder="Saisir le prix produit" value="<?php echo $resultp[0]["prixUnitaire"]?>"/>
        <br>
        
        <?php
        if (isset($_SESSION['erros_signup'])) {
          $errors = $_SESSION['erros_signup'];
          echo "<br>";
          foreach ($errors as $error) {
            echo '<p class="c-red">'.$error.'</p>';
          }
          unset($_SESSION['erros_signup']);
        }
        ?>

        <input class="bg-green" type="submit" value="verifier" />
      </form>
    </div>
  </div>
</body>

</html>