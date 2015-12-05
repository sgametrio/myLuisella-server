<?php
   //uncomment this if you don't handle ORIGIN in configuration file
   header("Access-Control-Allow-Origin: *");
       header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    
    require('globals.php'); 
      
    // Connection to DB
    try
    {
        $db = new PDO('mysql:host='.$DB_host.';dbname='.$DB_name.';charset=utf8', $DB_user, $DB_password);
        //error are handled as exception
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e)
    {
        echo 'ERROR: ' . $e -> getMessage();
        exit();
    }
    
    try
    {
      if(!isset($_POST["tableId"]))
         Throw new PDOException("Missing POST parameter.");
      $statement = $db -> prepare("DELETE FROM `food&order` WHERE `food&order`.orderId IN (SELECT `order`.orderId FROM `order` WHERE `order`.tableId = :tableId )");
      $statement -> execute(array(":tableId" => $_POST["tableId"]));
      $statement = $db -> prepare("DELETE FROM `order` WHERE order.tableId = :tableId ");
      $statement -> execute(array(":tableId" => $_POST["tableId"]));
      $statement = $db -> prepare("DELETE FROM `table` WHERE table.tableId = :tableId ");
      $statement -> execute(array(":tableId" => $_POST["tableId"]));
      echo 0;
   }
   catch (PDOException $e)
   {
      echo 'ERROR: ' . $e -> getMessage();
        exit();
   }
?>
