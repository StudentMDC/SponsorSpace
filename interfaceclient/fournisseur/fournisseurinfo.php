<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
if (isset($_SESSION["profil"])) {
  $profil = $_SESSION["profil"];
} else {
  $profil = 'avatar.png';
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $idf = $_POST["idFournisseur"];
  $_SESSION["idFournisseur"] = $idf;
  try {
    require_once "../../includes/dbh.inc.php";
    // Tirer les infos client fournisseur en utilisant la jointure interne
    $query = "SELECT `nomCommercialC`, `siegeSocialC`, `nomActiviteC`, `emailC`, fournisseur.idFournisseur
              FROM client
              INNER JOIN fournisseur
              ON fournisseur.usernameF=client.username
              WHERE idFournisseur=:idFournisseur;";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":idFournisseur", $idf);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tirer les infos produit du fournisseur en utilisant la jointure interne
    $queryp = "SELECT *
              FROM produit
              INNER JOIN fournisseur
              ON fournisseur.idFournisseur=produit.idFournisseur
              WHERE fournisseur.idFournisseur=:idFournisseur;";

    $stmtp = $pdo->prepare($queryp);

    $stmtp->bindParam(":idFournisseur", $idf);

    $stmtp->execute();

    $resultp = $stmtp->fetchAll(PDO::FETCH_ASSOC);
    // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
    $pdo = null;
    $stmt = null;
    $stmtp = null;
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Page Partenariat</title>
  <!-- Render all elms normally -->
  <!-- <link rel="stylesheet" href="css/normalise.css" /> -->
  <!-- Main CSS file -->
  <link rel="stylesheet" href="../../css/framework.css" />
  <link rel="stylesheet" href="../../css/master.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../css/all.min.css" />
  <style>
    .welcomeimg {
      margin-top: 20px;
      display: flex;
      text-align: center;
      flex-direction: column;
      justify-content: center;
      row-gap: 10px;
      align-items: center;
      position: relative;
    }
    .product {
      margin-top: 15px;
    }
    .welcomeimg .avatar {
      width: 80px;
      height: 80px;
      border-radius: 1px;
      margin-left: auto;
      margin-right: auto;
    }
    .product p{
      color: white;
    }
    .product p:nth-of-type(2){
      color: white;
      font-weight: bold;
    }
    .product p:first-of-type {
      color: white;
      font-weight: 700;
      text-transform: capitalize;
      font-style: italic;
      font-size: 25px;
    }
  </style>
</head>

<body>
  <div class="page d-flex">
    <div class="sidebar bg-white p-20 p-relative">
      <h3 class="p-relative txt-c mt-0">
      SponsorSpace
      </h3>
      <ul>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../dashboard.php">
            <i class="fa-regular fa-chart-bar fa-fw"></i>
            <span class="hide-mobile">Dashboard</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../profile.php">
            <i class="fa-regular fa-user fa-fw"></i>
            <span class="hide-mobile">Profile</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="tousfournisseurs.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les fournisseurs</span>
          </a>
        </li>
        <li>
          <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="chercherparthemeF.php">
            <i class="fa-solid fa-palette"></i>
            <span class="hide-mobile">Founisseurs par thème</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="devisEnvoye.php">
            <i class="fa-solid fa-file-circle-check"></i>
            <span class="hide-mobile">Devis Envoyés</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../gdo/toutgdo.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les grands donneurs d'ordre</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../gdo/chercherG.php">
            <i class="fa-solid fa-pen-to-square"></i>
            <span class="hide-mobile">grand donneurs d'ordre sous demande</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../gdo/demandesEnvoyee.php">
            <i class="fa-solid fa-envelope-circle-check"></i>
            <span class="hide-mobile">Demandes Envoyées</span>
          </a>
        </li>

      </ul>
      <a class="logout d-block d-flex align-center fs-14 c-black rad-6 p-10" href="../../logout.php">
            <i class="fa-solid fa-power-off"></i>
            <span class="hide-mobile">Log out</span>
      </a>
    </div>
    <div class="content w-full">
      <!-- START Head -->
      <div class="head bg-white p-15 between-flex">
        <div class="vh search p-relative">
          <input type="search" placeholder="Chercher un founisseur" class="p-10">
        </div>
        <div class="center-flex-g mr-10">
          <div class="icons">
            <a href="../profile.php">
              <img decoding="async" src="../../imgs/<?php echo $profil; ?>" alt="" />
            </a>
          </div>
          <h4><?php echo $utilisateur; ?></h4>
        </div>
      </div>
      <!-- /END Head -->
      <h1 class="p-relative ml-15">Page Partenariat</h1>
      <div id="wrapper" class="wrapper d-grid gap-20">
        <?php
        if (empty($result)) {
          echo '<div class="welcome bg-white rad-10 txt-c-mobile block-mobile">';
          echo "<h3>";
          echo "Pas de résultat";
          echo "</h3>";
          echo '</div>';
        } else {
          foreach ($result as $row) {
            echo '<div class="welcome bg-white rad-10 txt-c-mobile block-mobile">';
            echo '<h2 class="txt-c"> Nom commercial: ';
            echo '<span class="c-blue">' . $row["nomCommercialC"] . '</span>';
            echo "</h2>";
            echo "<br>";
            echo '<div class="txt-c center-flex-c">';
            echo '<i class="fa-solid fa-location-dot"></i>';
            echo '<h4>&nbsp;&nbsp;Siège social: ';
            echo "</h4>";
            echo '<p class="mt-10 txt-c">' . $row["siegeSocialC"] . '</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-c">';
            echo '<i class="fa-solid fa-briefcase"></i>';
            echo '<h4>&nbsp;&nbsp;Activité ou objet social: ';
            echo "</h4>";
            echo '<p class="txt-c mt-10">' . $row["nomActiviteC"] . '</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-c">';
            echo '<i class="fa-solid fa-envelope"></i>';
            echo '<h4>&nbsp;&nbsp;email: ';
            echo "</h4>";
            echo '<p class="txt-c mt-10">' . $row["emailC"] . '</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-c">';
            echo '<i class="fa-solid fa-tags"></i>';
            echo '<h4>&nbsp;&nbsp;Produits disponibles: ';
            echo "</h4>";
            echo '</div>';
            echo '<div class="beforep txt-c center-flex ml-15 mr-15">';
            foreach ($resultp as $row) {
              if (empty($row["imgP"])) {
                $row["imgP"] = 'course-04.jpg';
              }
              echo '<div class="product">';
              echo '<div class="welcomeimg">';
              echo '<img class="avatar" src="../../imgs/' . $row["imgP"] . '" alt="">';
              echo '</div>';
              echo '  <p class="txt-c" data-idprod="' . $row["idProduit"] . '">' . $row["nomProduit"] . '</p>';
              echo '  <p class="txt-c mt-10">' . $row["prixUnitaire"] . ' DH </p>';
              echo '  <p class="txt-c fw-200 mt-10">' . $row["designationProduit"] . '</p>';
              echo '</div>';
            }
            echo '</div>';
            echo '</div>';
          }
        }
        ?>
        <div class="welcome rad-10 txt-c-mobile block-mobile">
          <form class="form" action="devis.php" enctype="multipart/form-data" method="POST">
            <span class="">Date Evenement </span>
            <input type="datetime-local" name="dateEvenement" placeholder="Saisir la date de l'évènement">
            <span class="">Nom Evenement </span>
            <input type="text" name="nomDevis" placeholder="Saisir un titre représentant l'évènement">
            <span class="">Remise souhaitée</span>
            <input type="text" name="remiseDevis" placeholder="saisir la remise que vous souhaitez sur vos produits">
            <span class="">Modalités de collaboration</span>
            <input type="text" name="servicesDevis" placeholder="vous inclure dans un post instagram, inclure votre logo sur les vêtements...">
            <span>Choisissez votre logo d'entreprise</span>
            <input required class="visit p-15 d-block fs-14 rad-6 bg-blue c-white w-fit mb-15" type="file" name="logo">
            <div id="crpd"></div>
            <input id="valider" class="btn-shape" type="submit" value="Valider">

          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="fournisseurinfo.js"></script>
</body>

</html>