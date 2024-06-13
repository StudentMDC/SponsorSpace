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
if (isset($_SESSION["profilp"])) {
  $profilp = $_SESSION["profilp"];
}
else {
  $profilp = 'course-04.jpg';
}
try {
  require_once "../includes/dbh.inc.php";

  $queryp = "SELECT *
            FROM produit
            INNER JOIN fournisseur
            ON fournisseur.idFournisseur=produit.idFournisseur
            WHERE fournisseur.usernameF=:username;";

  $stmtp = $pdo->prepare($queryp);
  $stmtp->bindParam(":username", $utilisateur);
  $stmtp->execute();
  $resultp = $stmtp->fetchAll(PDO::FETCH_ASSOC);

  // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
  $pdo = null;
  $stmtp = null;
} catch (PDOException $e) {
  die("Connexion échouée: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mes produits</title>
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

    .dots {
      background-image: url("../imgs/dots.png");
      height: 186px;
      width: 204px;
      background-repeat: no-repeat;
      position: absolute;
    }

    .dots-up {
      top: 200px;
      right: 10px;
    }

    .dots-down {
      top: 50%;
      right: 314px;
    }

    @media (max-width: 767px) {
      .dots {
        display: none;
      }

      .dots-up {
        display: none;
      }

      .dots-down {
        display: none;
      }
    }

    @media (max-width: 992px) {
      .dots {
        display: none;
      }

      .dots-up {
        display: none;
      }

      .dots-down {
        display: none;
      }
    }
    .welcome {
      overflow: visible;
      border-top: 2px solid #ccc;
    }
    .welcomeimg {
      display: flex;
      justify-content: center;
      align-items: center; /* Optional: centers vertically */
      position: relative; /* Required if the .avatar position is absolute */
    }
    .welcomeimg .avatar {
      width: 80px;
      height: 80px;
      z-index: 3;
      border-radius: 1px;
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
        <li title="Dashboard">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="dashboard.php">
            <i class="fa-regular fa-chart-bar fa-fw"></i>
            <span class="hide-mobile">Dashboard</span>
          </a>
        </li>
        <li title="Profile">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="profile.php">
            <i class="fa-regular fa-user fa-fw"></i>
            <span class="hide-mobile">Profile</span>
          </a>
        </li>
        <li>
          <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="mesproduits.php">
            <i class="fa-solid fa-tags"></i>
            <span class="hide-mobile">Mes produits</span>
          </a>
        </li>
        <li title="Consulter tous les fournisseurs">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./fournisseur/tousfournisseurs.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les fournisseurs</span>
          </a>
        </li>
        <li title="Chercher des founisseurs par thème et demander un devis">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./fournisseur/chercherparthemeF.php">
            <i class="fa-solid fa-palette"></i>
            <span class="hide-mobile">Founisseurs par thème</span>
          </a>
        </li>
        <li title="Consulter tous vos devis Envoyés">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./fournisseur/devisEnvoye.php">
            <i class="fa-solid fa-file-circle-check"></i>
            <span class="hide-mobile">Devis Envoyés</span>
          </a>
        </li>
        <li title="Consulter tous vos devis Reçus">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./fournisseur/devisRecu.php">
            <i class="fa-solid fa-inbox"></i>
            <span class="hide-mobile">Devis Reçus</span>
          </a>
        </li>
        <li title="Consulter tous les grands donneurs d'ordre">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./gdo/tousgdo.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les grands donneurs d'ordre</span>
          </a>
        </li>
        <li title="Consulter tous les grands donneurs d'ordre et envoyer une demande">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./gdo/chercherG.php">
            <i class="fa-solid fa-pen-to-square"></i>
            <span class="hide-mobile">grand donneurs d'ordre sous demande</span>
          </a>
        </li>
        <li title="Consulter tous vos demandes envoyées">
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./gdo/demandesEnvoyee.php">
            <i class="fa-solid fa-envelope-circle-check"></i>
            <span class="hide-mobile">Demandes Envoyées</span>
          </a>
        </li>

      </ul>
      <a class="logout d-block d-flex align-center fs-14 c-black rad-6 p-10" href="../logout.php">
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
            <a href="profile.php">
              <img decoding="async" src="../imgs/<?php echo $profil; ?>" alt="" />
            </a>
          </div>
          <h4><?php echo $utilisateur; ?></h4>
        </div>
      </div>
      <!-- /END Head -->
      <h1 class="p-relative ml-15">Mes produits</h1>
      <div class="wrapper d-grid gap-20">
        <?php
        foreach ($resultp as $row) {
            if(empty($row["imgP"])){
              $row["imgP"] = 'course-04.jpg';
            }
            echo '<div class="welcome bg-white rad-10 txt-c-mobile block-mobile">';
            echo '<div class="welcomeimg">';
            echo '<img class="avatar" src="../imgs/'.$row["imgP"] .'" alt="">';
            echo '</div>';
            echo '<br>';
            echo '<div class="mt-15 around-flex">';
            echo '<form method="POST" action="updateimage.php"><input type="hidden" name="idProduit" value=' . $row["idProduit"] . '>';
            echo '<input class="ml-15 btn-shape boutonregarder" type="submit" value="modifier l\'image"></form>';
            echo '<form method="POST" action="updateprod.php"><input type="hidden" name="idProduit" value=' . $row["idProduit"] . '>';
            echo '<input class="ml-15 btn-shape boutonregarder" type="submit" value="modifier produit"></form>';
            echo '</div>';
            echo '<h2 class="txt-c"> Nom produit: ';
            echo '<span class="c-blue">' . $row["nomProduit"] . '</span>';
            echo "</h2>";
            echo "<br>";
            echo '<div class="center-flex">';
            echo '<i class="fa-solid fa-money-bill-wave"></i>';
            echo '<h4>&nbsp;&nbsp;Prix produit: ';
            echo '</div>';
            echo '<p class="txt-c mt-10">' . $row["prixUnitaire"] . '</p>';
            echo "</h4>";
            echo '<div class="center-flex">';
            echo '<i class="fa-solid fa-receipt"></i>';
            echo '<h4>&nbsp;&nbsp;Désignation produit: ';
            echo '</div>';
            echo '<p class="txt-c mt-10">' . $row["designationProduit"] . '</p>';
            echo "</h4>";
            echo '</div>';
            
          }
        ?>

        </div>
      </div>
</body>

</html>