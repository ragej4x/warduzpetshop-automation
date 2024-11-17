<?php 
      // Read the contents of the file "python/ph_log.txt"
      $file_content = file_get_contents("python/ph_log.txt");

      // Split the file into lines
      $lines = explode("\n", $file_content);

      // Process each line for formatting
      foreach ($lines as $line) {
          // Trim the line and skip if it's empty
          $line = trim($line);
          if (!empty($line)) {
              echo htmlspecialchars($line) . "<br />";
          }
      }
    ?>