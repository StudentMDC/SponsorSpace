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
try {
  require_once "../includes/dbh.inc.php";

  $query = "SELECT * from client WHERE username=:username;";

  $stmt = $pdo->prepare($query);

  $stmt->bindParam(":username", $utilisateur);

  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <title>Profile</title>
  <!-- Render all elms normally -->
  <!-- <link rel="stylesheet" href="css/normalise.css" /> -->
  <!-- Main CSS file -->
  <link rel="stylesheet" href="../css/master.css" />
  <link rel="stylesheet" href="../css/framework.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/all.min.css" />
  <style>
    .produitcontainer {
      display: flex;
      min-width: 80%;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      row-gap: 10px;
      background-color: inherit;
      text-align: center;
      border-radius: 5px;
    }

    .product {
      min-width: 80%;

    }

    .avatar {
      max-width: 100%;
      width: 100px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    .welcome div h4 {
      text-transform: uppercase;
      font-weight: 400;
      border-bottom:1px solid #ccc;
      padding-bottom: 5px;
      min-width: 60%;
    }

    .welcome {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 450px));
      column-gap: 40px;
      row-gap: 60px;
    }
    .product p{
      color: white;
    }
    .product p:nth-of-type(2){
      font-weight: 300;
    }
    .product p:first-of-type {
      font-weight: 600;
      font-size: 25px;
    }
    .product p:last-of-type {
      font-weight: 500;
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
          <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="profile.php">
            <i class="fa-regular fa-user fa-fw"></i>
            <span class="hide-mobile">Profile</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="mesproduits.php">
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
      <h1 class="p-relative ml-15">Welcome Back <?php echo $utilisateur; ?> </h1>
      <div id="wrapper" class="wrapper">
        <?php
        if (empty($result)) {
          echo '<div class="welcome bg-white rad-10 txt-c-mobile block-mobile">';
          echo "<h3>";
          echo "Pas de résultat";
          echo "</h3>";
          echo '</div>';
        } else {
        ?>
          <div class="txt-c ml-auto mr-auto w-full between-flex-c">
            <img class="avatar" src="../imgs/<?php echo $profil; ?>" alt="">
            <br>
            <a href="update.php" class="visit p-15 d-block fs-14 rad-6 bg-blue c-white w-fit">Update Profile</a>
          </div>
          <br>
          <hr>
        <?php
          foreach ($result as $row) {
            echo '<div class="welcome border-l-ccc bg-white rad-10 txt-c-mobile center-flex-c block-mobile">';
            echo '<div class="txt-c center-flex-g">';
            echo '<h4 class="f-g" >&nbsp;&nbsp; votre nom commercial: ';
            echo "</h4>";
            echo '<p class="txt-c c-blue fw-bold border-b-ccc">' . $row["nomCommercialC"]  . '</p>';
            // echo '<br> <p class="txt-c">Votre nom apparaît dans les documents (devis et demandes)</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-g">';
            echo '<h4 class="f-g" >&nbsp;&nbsp; votre Siège social:';
            echo "</h4>";
            echo '<p class="txt-c c-blue fw-bold border-b-ccc">' . $row["siegeSocialC"] . '</p>';
            // echo '<br><p class="txt-c">Votre addresse apparaît dans les documents (devis et demandes)</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-g">';
            echo '<h4 class="f-g" >&nbsp;&nbsp;Votre Activité: ';
            echo "</h4>";
            echo '<p class="txt-c c-blue fw-bold border-b-ccc">' . $row["nomActiviteC"] . '</p>';
            // echo '<br> <p class="txt-c">Votre activité apparaît dans les documents et sert de filtre pour la recherche (devis et demandes)</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-g">';
            echo '<h4 class="f-g" >&nbsp;&nbsp;Votre email: ';
            echo "</h4>";
            echo '<p class="txt-c c-blue fw-bold border-b-ccc">' . $row["emailC"] . '</p>';
            // echo '<br> <p class="txt-c">Votre email apparaît dans les documents (devis et demandes)</p>';
            echo '</div>';
            echo '<div class="txt-c center-flex-g">';
            echo '<h4 class="f-g" >&nbsp;&nbsp;Vos Produits: ';
            echo '</div>';
            echo '<div class="produitcontainer">';
            foreach ($resultp as $row) {
              echo '<div class="product">';
              echo '  <p class="txt-c">' . $row["nomProduit"] . '</p>';
              echo '  <p class="txt-c">' . $row["designationProduit"] . '</p>';
              echo '  <p class="txt-c">' . $row["prixUnitaire"] . ' DH </p>';
              echo '</div>';
            }
            // echo '<br> <p class="txt-c">Vos produits apparaîssent dans les devis</p>';
            echo '</div>';
            echo '</div>';
          }
        }
        ?>
      </div>
    </div>
  </div>

</body>

</html>