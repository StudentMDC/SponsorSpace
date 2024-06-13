<?php
    function is_Empty($password, $username)
    {
      if (empty($password) || empty($username)) {
        return true;
      }
      else {
        return false;
      }
    }

    function is_username_Taken(object $pdo, string $username)
    {
      $query = "SELECT username FROM client WHERE username = :username; ";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(":username", $username);
      $stmt->execute();
      $result = $stmt->fetch (PDO:: FETCH_ASSOC);
      if ($result) {
        return true;
      }
      else {
        return false;
      }
    }





