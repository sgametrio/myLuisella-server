<?php
//uncomment this if you don't handle ORIGIN in configuration file
   header("Access-Control-Allow-Origin: *");
       header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    require_once('./globals.php');  
    // Connection to DB
    try
    {
        $db = new PDO('mysql:host='.$DB_host.';dbname='.$DB_name.';charset=utf8', $DB_user, $DB_password);
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e -> getMessage();
        exit();
    }
    //Inserting new table into DB
    try
    {
        //Check POSTED parameters
        
        $statement = $db -> prepare("INSERT INTO `table` (`tableNumber`, `customers`, `tableName`) VALUES (:tableNumber, :customers, :tableName)");
        $statement -> execute(array(':tableNumber' => $_POST['tableNumber'], ':customers' => $_POST['customers'], ':tableName' => $_POST['tableName']));
        //If an exception isn't thrown then it's time to echo something!
        echo "Good";
    } catch (PDOException $e) {
        echo 'Exception: ' . $e -> getMessage();
    }
?>
