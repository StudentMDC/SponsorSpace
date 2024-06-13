<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $dateevent = date('Y-m-d H:i:s', strtotime($_POST["dateEvenement"]));
  $nomevent = $_POST["nomDemande"];
  $idGdo = $_SESSION["idGdo"];
  $objet = $_POST["objetDemande"];
  $PD = $_POST["prixDemande"];
  $servicesDemande = $_POST["servicesDemande"];

  try {
    require_once "../../includes/dbh.inc.php";

    // Nom Societe generateur de la demande
    $queryC = "SELECT * FROM client WHERE username = :username;";
    $stmtC = $pdo->prepare($queryC);
    $stmtC->bindParam(":username", $utilisateur);
    $stmtC->execute();
    $resultC = $stmtC->fetchAll(PDO::FETCH_ASSOC);
    $nomgenerateurdemandeC = $resultC[0]['nomCommercialC'];

    // Nom Societe destinataire de la demande
    $queryG = "SELECT nomCommercialG FROM gdo WHERE idGdo = :idGdo;";
    $stmtG = $pdo->prepare($queryG);
    $stmtG->bindParam(":idGdo", $idGdo);
    $stmtG->execute();
    $resultG = $stmtG->fetchAll(PDO::FETCH_ASSOC);
    $nomdestinatairedemandeC = $resultG[0]['nomCommercialG'];


    // Insertion normale
    $query = "INSERT INTO demande (username, idGdo, dateEvenementE, nomEvenementE, contenuE, servicesOffertsE, prixE) VALUES (:username, :idGdo, :dateEvenementE, :nomEvenementE, :contenuE, :servicesOfferts, :prixE);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $utilisateur);
    $stmt->bindParam(":idGdo", $idGdo);
    $stmt->bindParam(":dateEvenementE", $dateevent);
    $stmt->bindParam(":nomEvenementE", $nomevent);
    $stmt->bindParam(":contenuE", $objet);
    $stmt->bindParam(":servicesOfferts", $servicesDemande);
    $stmt->bindParam(":prixE", $PD);
    $stmt->execute();

    // selection de la table demande de toutes les lignes ayant les meme atraf et order by first done
    $queryf = "SELECT * FROM demande WHERE username = :username AND idGdo = :idGdo ORDER BY dateE ASC;";
    $stmtf = $pdo->prepare($queryf);
    $stmtf->bindParam(":username", $utilisateur);
    $stmtf->bindParam(":idGdo", $idGdo);
    $stmtf->execute();
    $resultf = $stmtf->fetchAll(PDO::FETCH_ASSOC);



    // Creation du fichier pdf a uploader dans la page demande Envoyées :)
    $name1 = "mademande" . str_replace(' ', '', $nomdestinatairedemandeC);
    $indexF = 0;
    $indexversion = 0;

    $firstFileCreated = false;
    foreach ($resultf as $row) {
      if ($indexF == 0) {
        if (!$firstFileCreated) {
          $mainFilePath = "../../mesdemandes/" . $name1 . ".php";
          if (!file_exists($mainFilePath)) {
            $demandeFile = fopen($mainFilePath, "a");
            $middle = '
                <?php
                require "../packages/vendor/autoload.php";
                use Dompdf\Dompdf;
                use Dompdf\Options;
                $options = new Options;
                $options->setIsRemoteEnabled(true);
                $dompdf = new Dompdf($options);
                $html = file_get_contents("../interfaceclient/gdo/contenupdfdemande.html");
                $html = str_replace([ "{{ nomgenerateur }}", "{{ adress }}", "{{ email }}", "{{ activite }}", "{{ nomGdo }}", "{{ nomEvenement }}", "{{ dateEvenement }}", "{{ date }}", "{{ prixE }}", "{{ contenu }}", "{{ services }}"], [  "' . $nomgenerateurdemandeC . '", "' . $resultC[0]['siegeSocialC'] . '", "' . $resultC[0]['emailC'] . '", "' . $resultC[0]['nomActiviteC'] . '", "' . $nomdestinatairedemandeC . '","' . $row["nomEvenementE"] . '", "' . $row["dateEvenementE"] . '", "' . date('Y-m-d', strtotime($row["dateE"])) . '", "' .  $row["prixE"] . '", "' . $row["contenuE"] . '", "' . $row["servicesOffertsE"] . '"], $html);
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream("mademande.pdf", ["Attachment" => 0]);
                ';
            fwrite($demandeFile, $middle);
            fclose($demandeFile);
          }
          $firstFileCreated = true;
        }
      } else {
          # code...
          $versionedFilePath = "../../mesdemandes/" . $name1 . "C" . $indexversion . ".php";
          if (!file_exists($versionedFilePath)) {
            $demandeversion = fopen($versionedFilePath, "a");
            $middle = '
                  <?php
                  require "../packages/vendor/autoload.php";
                  use Dompdf\Dompdf;
                  use Dompdf\Options;
                  $options = new Options;
                  $options->setIsRemoteEnabled(true);
                  $dompdf = new Dompdf($options);
                  $html = file_get_contents("../interfaceclient/gdo/contenupdfdemande.html");
                  $html = str_replace([ "{{ nomgenerateur }}", "{{ adress }}", "{{ email }}", "{{ activite }}", "{{ nomGdo }}", "{{ nomEvenement }}", "{{ dateEvenement }}", "{{ date }}", "{{ prixE }}", "{{ contenu }}", "{{ services }}"], [  "' . $nomgenerateurdemandeC . '", "' . $resultC[0]['siegeSocialC'] . '", "' . $resultC[0]['emailC'] . '", "' . $resultC[0]['nomActiviteC'] . '", "' . $nomdestinatairedemandeC . '","' . $row["nomEvenementE"] . '", "' . $row["dateEvenementE"] . '", "' . date('Y-m-d', strtotime($row["dateE"])) . '", "' .  $row["prixE"] . '", "' . $row["contenuE"] . '", "' . $row["servicesOffertsE"] . '"], $html);
                  $dompdf->loadHtml($html);
                  $dompdf->render();
                  $dompdf->stream("mademande.pdf", ["Attachment" => 0]);
                  ';
            fwrite($demandeversion, $middle);
            fclose($demandeversion);
          }
          $firstFileCreated = true;
        } 
        ++$indexversion;
      ++$indexF;       
    }

    
    




    // FREE UP RESSOURCESS BY CLOSINGCONNEXION &STATS
    $pdo = null;
    $stmt = null;
    $stmtC = null;
    $stmtf = null;

    header("Location: tousgdo.php");
    die();
  } catch (PDOException $e) {
    die("Connexion échouée: " . $e->getMessage());
  }
} else {
  header("Location: ../db.php");
}
