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
try {
  require_once "../../includes/dbh.inc.php";
  // Tirer les fournisseurs
  $query = "SELECT `nomCommercialC`, `siegeSocialC`, `nomActiviteC`, `profilimgC`
                    FROM client
                    INNER JOIN fournisseur
                    ON fournisseur.usernameF=client.username
                    WHERE fournisseur.usernameF!=:user;";

  $stmt = $pdo->prepare($query);
  $stmt->bindParam(":user", $utilisateur);

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
  <title>Page De Recherche</title>
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
    .welcome .avatar {
      position: absolute;
      z-index: 3;
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
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../mesproduits.php">
            <i class="fa-solid fa-tags"></i>
            <span class="hide-mobile">Mes produits</span>
          </a>
        </li>
        <li>
          <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="tousfournisseurs.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les fournisseurs</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="chercherparthemeF.php">
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
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="devisRecu.php">
            <i class="fa-solid fa-inbox"></i>
            <span class="hide-mobile">Devis Reçus</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../gdo/tousgdo.php">
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
        <a class="logout d-block d-flex align-center fs-14 c-black rad-6 p-10" href="../../logout.php">
              <i class="fa-solid fa-power-off"></i>
              <span class="hide-mobile">Log out</span>
        </a>
      </ul>
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
      <h1 class="p-relative ml-15">Tous les fournisseurs</h1>
      <div id="wrapper" class="wrapper d-grid gap-20">
        <?php
        if (empty($result)) {
          echo '<div class="welcome bg-white rad-10 txt-c-mobile block-mobile">';
          echo "<h3>";
          echo "Pas de résultat pour cette activité";
          echo "</h3>";
          echo '</div>';
        } else {
          foreach ($result as $row) {
            echo '<div class="welcome bg-white rad-10 txt-c-mobile block-mobile">';
            echo '<img class="avatar" src="../../imgs/office.png" alt="">';            echo '<br>';
            echo '<h2 class="txt-c"> Nom commercial: ';
            echo '<span class="c-blue">' . $row["nomCommercialC"] . '</span>';
            echo "</h2>";
            echo "<br>";
            echo '<div class="center-flex">';
            echo '<i class="fa-solid fa-location-dot"></i>';
            echo '<h4>&nbsp;&nbsp;Siège social: ';
            echo '</div>';
            echo '<p class="mt-10 txt-c">' . $row["siegeSocialC"] . '</p>';
            echo "</h4>";
            echo '</div>';
          }
        }
        ?>

      </div>
    </div>
  </div>
</body>

</html>