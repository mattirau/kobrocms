<?php

/*
 * This class includes helper fuctions
 */

class Helper {
   /*
    * Escapes user input for displaying on page
    * @param string $input User input
    * @return string Escaped string
    */

   public static function escapeForDisplay($input) {
      if (get_magic_quotes_gpc()) {
         $input = stripslashes($input);
      }
      $result = htmlspecialchars($input, ENT_QUOTES);
      return $result;
   }

}

?>
