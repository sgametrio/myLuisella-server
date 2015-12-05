<?php
 /*
   * Password Hashing With PBKDF2 (http://crackstation.net/hashing-security.htm).
   * Copyright (c) 2013, Taylor Hornby
   * All rights reserved.
   *
   * Redistribution and use in source and binary forms, with or without
   * modification, are permitted provided that the following conditions are met:
   *
   * 1. Redistributions of source code must retain the above copyright notice,
   * this list of conditions and the following disclaimer.
   *
   * 2. Redistributions in binary form must reproduce the above copyright notice,
   * this list of conditions and the following disclaimer in the documentation
   * and/or other materials provided with the distribution.
   *
   * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
   * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
   * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
   * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
   * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
   * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
   * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
   * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
   * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
   * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
   * POSSIBILITY OF SUCH DAMAGE.
   */
//uncomment this if you don't handle ORIGIN in configuration file
   header("Access-Control-Allow-Origin: *");
       header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
   require_once('globals.php');
   require_once('PasswordHash.php');


   // Connection to DB
   try
   {
      $db = new PDO('mysql:host='.$DB_host.';dbname='.$DB_name.';charset=utf8', $DB_user, $DB_password);
      $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }
   catch (PDOException $e)
   {
      echo 'ERROR: ' . $e -> getMessage();
      exit();
   }

   // FETCH DATA FROM INPUT FIELD
   try
   {
      $statement = $db -> prepare("SELECT * FROM `waitress` WHERE name=:name");
      $statement -> execute(array(':name' => $_POST['username']));
      if(count($statement))
      {
         $checkpass = "";
         //fetching the passwordHash to compare
         while($row = $statement -> fetch()) {
            $checkpass = $row['passwordHash'];
            $userId = $row['waitressId'];
         }
         if($checkpass == "")
            throw new PDOException("Account not found. Register before login, please.");
         if($result = validate_password($_POST['password'], $checkpass))
         {
            echo $userId;
         }
         else
            echo "Bad";
      }
      else
         throw new PDOException("User not registered, please sign up before logging in.");
   }
   catch(PDOException $e)
   {
      echo 'ERROR: ' . $e -> getMessage();
   }
?>
