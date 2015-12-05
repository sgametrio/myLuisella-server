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
      echo 'ERROR: ' . $e ->getMessage();
      exit();
   }

   // Fetching FORM data with Prepared Statement
   try
   {
      //Validate admin PW with inserted one
      $query = $db -> query("SELECT * FROM `waitress` WHERE `name` = 'admin'");
      //Check if admin account exists.
      if(count($query))
      {
         while ($row = $query -> fetch()) {
            $checkadmin = $row['passwordHash'];
         }
         //Don't check if there's something to escape beacuse I pass the parameter, received by POST to a function. I'm not doing anything with that on the DB.
         if($result = validate_password($_POST['admin'], $checkadmin))
         {
            $passwordHash = create_hash($_POST['password']);
            //This if statement is only for safety. Condition can be removed.
            if($result = validate_password($_POST['password'], $passwordHash))
            {
               //we assume that there is only one account with a specified username, so we check if there is already one.
               $statement = $db -> prepare("SELECT * FROM `waitress` WHERE `name`=:name");
               $statement -> execute(array(':name' => $_POST['username']));
               while($row = $statement -> fetch())
                  throw new PDOException("User already registered, please change username.");
               //All it's ok, then we have to execute a PS to insert new user
               $statement = $db -> prepare("INSERT INTO `waitress` (`name`, `passwordHash`) VALUES (:name, :passwordHash)");
               $statement -> execute(array(':name' => $_POST['username'],
                                    ':passwordHash' => $passwordHash));
               //If an exception isn't thrown then it's time to echo something and to store something
               setcookie("loggedUser", $_POST['username'], time() + 3600*5); //cookie expire in 5 hours
               //workaround for cookie
               $_COOKIE["loggedUser"] = $_POST['username'];
               echo "Good";
            }
         }
         else
            echo "Bad";
      }
      else
         throw new PDOException('Admin account not found. Please contact DB administrator.');
   }
   catch (PDOException $e)
   {
      echo 'Exception: ' . $e ->getMessage();
   }
?>
