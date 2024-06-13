<?php
session_start();
if (isset($_SESSION["utilisateur"])) {
  $utilisateur = $_SESSION["utilisateur"];
}
$idp = $_POST["idProduit"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Image</title>
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
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
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
      <h1 id="new" class="c-bblue">Changer image du produit</h1>
      <form action="updateimagev.php" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="idProduit" value="<?php echo $idp; ?>">
        <label for="nomcommercial">Choisissez votre image de produit</label>
        <input class="visit p-15 d-block fs-14 rad-6 bg-blue c-white w-fit ml-15 mb-15" type="file" name="image">
        <input class="bg-green" type="submit" value="verifier" />
      </form>
    </div>
  </div>
</body>

</html>