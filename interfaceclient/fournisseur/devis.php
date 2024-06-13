<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $file_name = $_FILES['logo']['name'];
  $dateevent = date('Y-m-d H:i:s', strtotime($_POST["dateEvenement"]));
  $nomevent = $_POST["nomDevis"];
  $idF = $_SESSION["idFournisseur"];
  $nbrPrd = $_POST["nbrPrd"];
  
  // Utilisation de la session pour le devis PDF
  $_SESSION["nbrPrd"] = $nbrPrd;
  $remise = $_POST["remiseDevis"];
  $servicesDevis = $_POST["servicesDevis"];
  require "./contenupdfdevis.php";
  try {
    require_once "../../includes/dbh.inc.php";

    // Nom Société générateur de la demande
    $queryC = "SELECT * FROM client WHERE username = :username;";
    $stmtC = $pdo->prepare($queryC);
    $stmtC->bindParam(":username", $utilisateur);
    $stmtC->execute();
    $resultC = $stmtC->fetchAll(PDO::FETCH_ASSOC);
    $nomgenerateurdemandeC = $resultC[0]['nomCommercialC'];

    // Nom Société destinataire de la demande
    $queryG = "SELECT `nomCommercialC`
              FROM client
              INNER JOIN fournisseur
              ON fournisseur.usernameF=client.username
              WHERE idFournisseur=:idFournisseur;";
    $stmtG = $pdo->prepare($queryG);
    $stmtG->bindParam(":idFournisseur", $idF);
    $stmtG->execute();
    $resultG = $stmtG->fetchAll(PDO::FETCH_ASSOC);
    $nomdestinatairedemandeC = $resultG[0]['nomCommercialC'];

    // Insertion devis
    $query = "INSERT INTO devis (usernameC , idFournisseur , dateEvenementV, nomEvenementV, remise, servicesOffertsV, imggenerateur) VALUES ( :username , :idF , :dateEvenementV, :nomEvenementV, :remise, :servicesOfferts, :imggenerateur);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $utilisateur);
    $stmt->bindParam(":idF", $idF);
    $stmt->bindParam(":dateEvenementV", $dateevent);
    $stmt->bindParam(":nomEvenementV",   $nomevent);
    $stmt->bindParam(":remise",   $remise);
    $stmt->bindParam(":servicesOfferts",  $servicesDevis);
    $stmt->bindParam(":imggenerateur",  $file_name);
    $stmt->execute();

    // Récupération de l'ID du devis inséré
    $idDevis = $pdo->lastInsertId();

    // Sélection de la table devis pour le PDF de toutes les lignes ayant les meme atraf et order by first done
    $querydevisselect = "SELECT * FROM devis 
                        WHERE usernameC = :username 
                        AND idFournisseur = :idF 
                        ORDER BY dateV ASC;";

    $stmtdevisselect = $pdo->prepare($querydevisselect);
    $stmtdevisselect->bindParam(":username", $utilisateur);
    $stmtdevisselect->bindParam(":idF", $idF);
    $stmtdevisselect->execute();
    $resultdevisselect = $stmtdevisselect->fetchAll(PDO::FETCH_ASSOC);

    // Insertion dans la table concerner
    $idproducts = $_POST["I"];
    $qteps = $_POST["QP"];

    for ($i = 0; $i < count($qteps); $i++) {
      $id = $idproducts[$i];
      $qtep = $qteps[$i];
      $queryinsertconcerner = "INSERT INTO concerner(`idProduit`, `idDevis`, `qteProduit`) VALUES (:idProduit, :idDevis, :qteProduit);";
      $stmtinsertconcerner = $pdo->prepare($queryinsertconcerner);
      $stmtinsertconcerner->bindParam(":idProduit", $id);
      $stmtinsertconcerner->bindParam(":idDevis", $idDevis); // Utilisation de l'ID récupéré
      $stmtinsertconcerner->bindParam(":qteProduit", $qtep);
      $stmtinsertconcerner->execute();
    }

    // Sélection de la table PRODUIT pour le PDF
    $queryconcernerselect = "SELECT `nomProduit`, `prixUnitaire`, `designationProduit`, concerner.qteProduit
                            FROM produit
                            INNER JOIN concerner
                            ON concerner.idProduit=produit.idProduit
                            WHERE concerner.idDevis=:idDevis;";
    $stmtconcernerselect = $pdo->prepare($queryconcernerselect);
    $stmtconcernerselect->bindParam(":idDevis", $idDevis); // Utilisation de l'ID récupéré
    $stmtconcernerselect->execute();
    $resultconcernerselect = $stmtconcernerselect->fetchAll(PDO::FETCH_ASSOC);

    // Création du fichier PDF à uploader dans la page demande envoyées
    $name1 = "mondevis" . str_replace(' ', '', $nomdestinatairedemandeC);
    $indexF = 0;
    $total = 0;
    $indexversion = 0;
    $mmidle = '';

    for ($i = 0; $i < count($resultconcernerselect); ++$i) {
      $row = $resultconcernerselect[$i];
      $index = $i + 1;
      if ($index <= $nbrPrd) {
        $mmidle .= '
          $html = str_replace(["{{ Produit ' . $index . ' }}",  "{{ PU ' . $index . ' }}", "{{ Designation ' . $index . ' }}", "{{ Prix ' . $index . ' }}", "{{ Quantite ' . $index . ' }}"], ["' . $row["nomProduit"] . '", "' . $row["prixUnitaire"] . '","' . $row["designationProduit"] . '","' . $row["prixUnitaire"] * $row["qteProduit"] . '", "' . $row["qteProduit"] . '"], $html);
          ';
        $total += $row["prixUnitaire"] * $row["qteProduit"];
      }
    }
    $mmidle .= '
      $html = str_replace(["{{ total }}"], ["' . $total . '"], $html);
    ';
    // Creation des fichiers PDF
    $firstFileCreated = false;
    foreach ($resultdevisselect as $row) {
      if ($indexF == 0) {
        if (!$firstFileCreated) {
          $mainFilePath = "../../mesdevis/" . $name1 . ".php";
          if (!file_exists($mainFilePath)) {
            $demandeFile = fopen($mainFilePath, "a");
            $middle = '
            <?php
                  require "../packages/vendor/autoload.php";
                  use Dompdf\Dompdf;
                  use Dompdf\Options;
                  $options = new Options;
                  $options->setIsRemoteEnabled(true);
                  $dompdf = new Dompdf([
                    "chroot" => "../imgs"
                  ]);
                  $html = file_get_contents("../interfaceclient/fournisseur/contenupdfdevis.html");
                  $html = str_replace([ "{{ nomgenerateur }}","{{ adress }}", "{{ profil }}", "{{ email }}", "{{activite}}", "{{ nomF }}", "{{ nomEvenement }}", "{{ dateEvenement }}", "{{ date }}", "{{ contenu }}", "{{ services }}"], [  "'.$nomgenerateurdemandeC.'","' . $resultC[0]['siegeSocialC'] . '","' . $row["imggenerateur"]  . '","' . $resultC[0]['emailC'] . '", "'.$resultC[0]['nomActiviteC'].'", "' . $nomdestinatairedemandeC . '","' . $row["nomEvenementV"] . '", "' . $row["dateEvenementV"] . '", "' . date('Y-m-d', strtotime($row["dateV"])) . '", "' . $row["remise"] . '", "' . $row["servicesOffertsV"] . '"], $html);';
            $end = '
                    $dompdf->loadHtml($html);
                    $dompdf->render();
                    $dompdf->stream("mondevis.pdf", ["Attachment" => 0]);
                    ';
            $chaine = $middle . $mmidle . $end;
            fwrite($demandeFile, $chaine);
            fclose($demandeFile);
            $firstFileCreated = true;
          }
        }
      } else {
        $versionedFilePath = "../../mesdevis/" . $name1 . "C" . $indexversion . ".php";
        if (!file_exists($versionedFilePath)) {
          $demandeversion = fopen($versionedFilePath, "a");
          $middle = '
            <?php
                  require "../packages/vendor/autoload.php";
                  use Dompdf\Dompdf;
                  use Dompdf\Options;
                  $options = new Options;
                  $options->setIsRemoteEnabled(true);
                  $dompdf = new Dompdf([
                    "chroot" => "../imgs"
                  ]);
                  $html = file_get_contents("../interfaceclient/fournisseur/contenupdfdevis.html");
                  $html = str_replace([ "{{ nomgenerateur }}","{{ adress }}", "{{ profil }}", "{{ email }}", "{{activite}}", "{{ nomF }}", "{{ nomEvenement }}", "{{ dateEvenement }}", "{{ date }}", "{{ contenu }}", "{{ services }}"], [  "'.$nomgenerateurdemandeC.'","' . $resultC[0]['siegeSocialC'] . '","' . $row["imggenerateur"]  . '","' . $resultC[0]['emailC'] . '", "'.$resultC[0]['nomActiviteC'].'", "' . $nomdestinatairedemandeC . '","' . $row["nomEvenementV"] . '", "' . $row["dateEvenementV"] . '", "' . date('Y-m-d', strtotime($row["dateV"])) . '", "' . $row["remise"] . '", "' . $row["servicesOffertsV"] . '"], $html);';
          $end = '
                  $dompdf->loadHtml($html);
                  $dompdf->render();
                  $dompdf->stream("mondevis.pdf", ["Attachment" => 0]);
                  ';
          $chaine = $middle . $mmidle . $end;
          fwrite($demandeversion, $chaine);
          fclose($demandeversion);
        }
        $firstFileCreated = true;
      }
      ++$indexversion;
    ++$indexF;
    }

    // Libérer les ressources en fermant la connexion et les déclarations
    $pdo = null;
    $stmt = null;
    $stmtC = null;
    $stmtdevisselect = null;
    $stmtinsertconcerner = null;

    header("Location: tousfournisseurs.php");
    die();
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
