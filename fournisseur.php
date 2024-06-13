<?php
session_start();
try {
  require_once "includes/dbh.inc.php";

  $query = "SELECT * from activite;";

  $stmt = $pdo->prepare($query);

  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
  $pdo = null;
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
  <title>Sign UP</title>
  <!-- Render all elms normally -->
  <!-- <link rel="stylesheet" href="css/normalise.css" /> -->
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
    body {
      background-color: #f1f5f9;
      position: relative;
    }

    .page {
      text-align: center;
      height: 100%;
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
      top: 0%;
      left: 50%;
      transform: translateX(-50%);
    }

    #new::before,
    #new::after {
      content: "";
      position: absolute;
      width: 0;
      height: 0;
    }

    #button {
      position: absolute;
      right: 0;
      border-radius: 5px;
      border: none;
      background-color: var(--grey-color);
      color: white;
      font-weight: bold;
      padding: 10px;
      transition: all 0.3s;
    }

    #button:hover {
      background-color: var(--blue-alt-color);
      color: white;
      padding-right: 20px;
      padding-left: 20px;
      cursor: pointer;
    }

    #up {
      position: absolute;
      position: fixed;
      right: 300px;
      bottom: 100px;
      width: 0;
      height: 0;
      border-width: 25px;
      border-style: solid;
      border-color: transparent transparent blue transparent;
    }

    #down {
      position: absolute;
      position: fixed;
      right: 200px;
      bottom: 75px;
      width: 0;
      height: 0;
      border-width: 25px;
      border-style: solid;
      border-color: blue transparent transparent transparent;
    }
  </style>
</head>

<body>
  <div class="page">
    <!-- START Head -->
    <div class="container bg-white p-15 center-flex-c txt-c">
      <h1 id="new" class="c-bblue">Sign UP</h1>
      <form action="fournisseurv.php" method="POST">

        <label for="nomcommercial">Nom commercial</label>
        <input type="text" name="nomcommercial" id="nomcommercial" placeholder="Saisir le nom commercial" />

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Saisir l'adresse email" />

        <label for="siege_social">Siège social</label>
        <input type="text" name="siege_social" id="siege_social" placeholder="Saisir l'adresse du siège social" />

        <label for="activite">Activité ou objet social</label>
        <?php
        echo '<select title="select" name="nomActivite">';
        foreach ($result as $row) {
          echo '<option value="' . $row["nomActivite"] . '">' . $row["nomActivite"] . '</option>';
        }
        echo '</select>';
        ?>
        <br>
        <input type="button" id="button" value="Ajouter des produits" />
        <div id="boutondiv">
          <div>
            <p class="prdp">Produit 1</p>
            <input type="text" name="P[]" class="nouveauinput">
            <p class="prdp">Désignation produit 1</p>
            <input type="text" name="D[]" class="nouveauinput">
            <p class="prdp">Prix unitaire en DH du produit 1</p>
            <input type="number" name="PU[]" class="nouveauinput nprd"> <br>
          </div>
        </div>
        <br>
        <dialog>
          <div class="txt-c">
            <h4 class="ml-15 mr-15 c-black">Veuillez remplir proprement tous les champs des produits ! </h4>
          </div>
        </dialog>
        <?php
        if (isset($_SESSION['erros_signup'])) {
          $errors = $_SESSION['erros_signup'];
          echo "<br>";
          foreach ($errors as $error) {
            echo '<p class="c-red">' . $error . '</p>';
          }
          unset($_SESSION['erros_signup']);
        }
        ?>

        <input class="bg-green" type="submit" value="verifier" />
      </form>
    </div>
  </div>
  <a href="#">
    <div id="up"></div>
  </a>
  <!-- <a href=""><div id="down"></div></a> -->
  <script defer src="fournisseur.js"></script>
</body>

</html>