<?php
//uncomment this if you don't handle ORIGIN in configuration file
   header("Access-Control-Allow-Origin: *");
       header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    require_once('globals.php');
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

    try
    {
        //have to find the table with passed id and then update its information.
      $statement = $db -> prepare("UPDATE `table` SET `tableName` = :tableName, `tableNumber` = :tableNumber, `customers` = :customers WHERE `tableId` = :tableId");
        $statement -> execute(array(':tableId' => $_POST['tableId'],
                                    ':tableName' => $_POST['tableName'],
                                    ':tableNumber' => $_POST['tableNumber'],
                                    ':customers' => $_POST['customers']));
        
        //It's modified, retrieve 0.
        echo "0"; 
    }
    catch(PDOException $e)
    {
        echo 'ERROR: ' . $e ->getMessage();
    }
?>
