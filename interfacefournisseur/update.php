<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
try {
  
  require_once "../includes/dbh.inc.php";

  $query = "SELECT * from activite;";

  $stmt = $pdo->prepare($query);

  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // value of form

  $queryU = "SELECT * from client WHERE username=:username;";
  $stmtU = $pdo->prepare($queryU);
  $stmtU->bindParam(":username", $utilisateur);
  $stmtU->execute();
  $resultU = $stmtU->fetchAll(PDO::FETCH_ASSOC);

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
  <title>Update Profile</title>
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
    body {
      background-color: #f1f5f9;
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
  </style>
</head>

<body>
  <div class="page">
    <!-- START Head -->
    <div class="container bg-white p-15 center-flex-c txt-c">
      <h1 id="new" class="c-bblue">Update Profile</h1>

      <form action="updatev.php" enctype="multipart/form-data" method="POST" >
        <label for="nomcommercial">Choisissez votre photo de profil</label>
        <input class="visit p-15 d-block fs-14 rad-6 bg-blue c-white w-fit ml-15 mb-15" type="file" name="image">
        <label for="nomcommercial">Nom commercial</label>
        <input type="text" name="nomcommercial" id="nomcommercial" placeholder="Saisir le nom commercial" value="<?php echo $resultU[0]["nomCommercialC"]?>"/>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Saisir l'adresse email" value="<?php echo $resultU[0]["emailC"]?>"/>

        <label for="siege_social">Siège social</label>
        <input type="text" name="siege_social" id="siege_social" placeholder="Saisir l'adresse du siège social" value="<?php echo $resultU[0]["siegeSocialC"]?>"/>

        <label for="activite">Activité ou objet social</label>
        <?php
        echo '<select title="select" name="nomActivite">';
        echo '<option value="' . $resultU[0]["nomActiviteC"] . '">' . $resultU[0]["nomActiviteC"] . '</option>';
        foreach ($result as $row) {
          echo '<option value="' . $row["nomActivite"] . '">' . $row["nomActivite"] . '</option>';
        }
        echo '</select>';
        ?>
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