<?php 
      $file_content = file_get_contents("python/ph_log.txt");

      $lines = explode("\n", $file_content);

      foreach ($lines as $line) {
          $line = trim($line);
          if (!empty($line)) {
              echo htmlspecialchars($line) . "<br />";
          }
      }
    ?>