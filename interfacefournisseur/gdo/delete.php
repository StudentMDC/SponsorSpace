<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['filename'])) {
    $filename = basename($_POST['filename']);
    $filepath = "../../mesdemandes/$filename";

    if (file_exists($filepath)) {
      if (unlink($filepath)) {
        // Redirect back to the previous page with a success message
        header("Location: demandesEnvoyee.php?delete=success");
        exit();
      } else {
        // Redirect back to the previous page with an error message
        header("Location: demandesEnvoyee.php?delete=error");
        exit();
      }
    } else {
      // File does not exist
      header("Location: demandesEnvoyee.php?delete=notfound");
      exit();
    }
  } else {
    // Invalid request
    header("Location: demandesEnvoyee.php?delete=invalid");
    exit();
  }
} else {
  // Invalid request method
  header("Location: demandesEnvoyee.php?delete=method");
  exit();
}
