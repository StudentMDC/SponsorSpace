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

  $query = "SELECT * from client WHERE username=:username;";

  $stmt = $pdo->prepare($query);

  $stmt->bindParam(":username", $utilisateur);

  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $nomsociete = $result[0]["nomCommercialC"];
} catch (PDOException $e) {
  die("Connexion échouée: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mes demandes</title>
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
    ::-webkit-scrollbar-thumb {
      background-color: var(--demande-color);
    }

    ::-webkit-scrollbar-thumb:hover {
      background-color: var(--blue-alt-color);
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
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="../fournisseur/devisRecu.php">
            <i class="fa-solid fa-inbox"></i>
            <span class="hide-mobile">Devis Reçus</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="tousgdo.php">
            <i class="fa-solid fa-diagram-project fa-fw"></i>
            <span class="hide-mobile">Tous les grands donneurs d'ordre</span>
          </a>
        </li>
        <li>
          <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="chercherG.php">
          <i class="fa-solid fa-pen-to-square"></i>
            <span class="hide-mobile">grand donneurs d'ordre sous demande</span>
          </a>
        </li>
        <li>
          <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="demandesEnvoyee.php">
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
      <h1 class="p-relative ml-15">Demandes Envoyées</h1>
      <div class="wrapper">

        <div class="testimonials">
          <p>
            Trouvez toutes vos demandes en une seule page
          </p>

          <?php
          $dir = new DirectoryIterator(dirname("../../mesdemandes/."));
          foreach ($dir as $fileinfo) {
            if ($fileinfo->isDot() || !$fileinfo->isFile()) {
              continue;
            }

            $filename = $fileinfo->getFilename();
            $filepath = "../../mesdemandes/$filename";

            $filecontenu = file_get_contents($filepath);
            $stringcontenu = '"{{ services }}"], [  "' . $nomsociete . '",';

            if (str_contains($filecontenu, $stringcontenu)) {
              echo  '
                      <div class="content">
                          <img decoding="async" src="../../imgs/email.png" alt="" />
                          <div class="text">
                            ' . htmlspecialchars($filename) . '
                            <div class="ikon">
                            <form class="delete-form" action="delete.php" method="POST">
                              <input type="hidden" name="filename" value="' . htmlspecialchars($filename) . '">
                              <button type="submit" class="delete-icon b-none">
                                <i class="fa-solid fa-trash-can fa-lg" style="color: #B80000;"></i>
                              </button>
                            </form>
                            <a target="_blank" href="../../mesdemandes/' . htmlspecialchars($filename) . '">
                              <i class="fa-solid fa-download fa-lg" style="color: #44b6fe;"></i>
                            </a>
                            </div>
                          </div>
                      </div>
                    ';
            }
          }
          ?>





        </div>

      </div>
    </div>
  </div>
  </div>
  <script defer src="demandesEnvoyee.js"></script>
</body>

</html>