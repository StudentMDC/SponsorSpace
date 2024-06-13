<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
if (isset($_SESSION["profil"])) {
  $profil = $_SESSION["profil"];
}
else {
  $profil = 'avatar.png';
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $idg = $_POST["idGdo"];
  $_SESSION["idGdo"] = $idg;
  try {
    require_once "../../includes/dbh.inc.php";

    $query = "SELECT * from gdo WHERE idGdo=:idGdo;";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":idGdo", $idg);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
    $pdo = null;
    $stmt = null;
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../../db.php");
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
    .welcome img {
      max-width: 100%;
      height: 100px;
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
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../fournisseur/tousfournisseurs.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les fournisseurs</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../fournisseur/chercherparthemeF.php">
            <i class="fa-solid fa-palette"></i>
            <span class="hide-mobile">Founisseurs par thème</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../fournisseur/devisEnvoye.php">
            <i class="fa-solid fa-file-circle-check"></i>
            <span class="hide-mobile">Devis Envoyés</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="tousgdo.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les grands donneurs d'ordre</span>
          </a>
        </li>
        <li>
          <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="chercherG.php">
          <i class="fa-solid fa-pen-to-square"></i>
            <span class="hide-mobile">grand donneurs d'ordre sous demande</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="demandesEnvoyee.php">
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
          echo '<div class="txt-c">';
          echo '<img class="c-blue" src="../../imgs/' . $row["profilimgG"] . '">';
          echo "</div>";
          echo '<h2 class="txt-c"> Nom commercial: ';
          echo '<span class="c-blue">' . $row["nomCommercialG"] . '</span>';
          echo "</h2>";
          echo "<br>";
          echo '<div class="txt-c center-flex-c">';
          echo '<i class="fa-solid fa-location-dot"></i>';
          echo '<h4>&nbsp;&nbsp;Siège social: ';
          echo "</h4>";
          echo '<p class="mt-10 txt-c">' . $row["siegeSocialG"] . '</p>';
          echo '</div>';
          echo '<div class="txt-c center-flex-c">';
          echo '<i class="fa-solid fa-globe"></i>';
          echo '<h4>&nbsp;&nbsp;Site Web: ';
          echo "</h4>";
          echo '<p class="txt-c mt-10">' . $row["siteWeb"] . '</p>';
          echo '</div>';
          echo '<div class="txt-c center-flex-c">';
          echo '<i class="fa-solid fa-envelope"></i>';
          echo '<h4>&nbsp;&nbsp;email: ';
          echo "</h4>";
          echo '<a href="mailto:' . $row["emailG"] . '"><p class="txt-c mt-10">' . $row["emailG"] . '</p></a>';
          echo '</div>';
          echo '</div>';
        }
      }
      ?>
      <div class="welcome rad-10 txt-c-mobile block-mobile">
        <form class="form" action="demande.php" method="POST">
          <span class="">Date Evenement </span>
          <input type="datetime-local" name="dateEvenement" placeholder="Saisir la date de l'évènement">
          <span class="">Nom Evenement </span>
          <input type="text" name="nomDemande" placeholder="Saisir un titre représentant l'évènement">
          <span class="">Prix souhaité en DH</span>
          <input type="text" name="prixDemande" placeholder="Saisir le prix souhaité du gdo">
          <span class="">Objet de la demande </span>
          <input type="text" name="objetDemande" placeholder="Saisir vos motivations vos progets etc...">
          <span class="">Modalités de collaboration</span>
          <input type="text" name="servicesDemande" placeholder="Post instagram, logo sur vêtement...">
          <input class="btn-shape" type="submit" value="Envoyer">
        </form>
      </div>
    </div>
    </div>
  </div>
</body>

</html>