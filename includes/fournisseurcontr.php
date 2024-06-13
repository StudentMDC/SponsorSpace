<?php
    function is_Empty($nomcommercial, $siege_social, $email, $activite)
    {
      if (empty($nomcommercial) || empty($siege_social) || empty($email) || empty($activite)) {
        return true;
      }
      else {
        return false;
      }
    }
    function is_Email_Invalid($email)
    {
      // Return tru if it is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
      }
      else {
        return false;
      }
    }




