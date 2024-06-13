<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $choix = $_POST["choix"];
  if ($choix==="Client") {
    header("Location: ../client.php");
  }
  else {
    header("Location: ../fournisseur.php");
    die();
  } 
} 
else {
  header("Location: ../db.php");
}
?>