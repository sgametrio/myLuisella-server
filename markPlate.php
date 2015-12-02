<?php
//uncomment this if you don't handle ORIGIN in configuration file
	header("Access-Control-Allow-Origin: *");
    	header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    
    require_once('globals.php'); 
		
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
		if(!isset($_POST["foodId"]) || !isset($_POST["orderId"]))
			Throw new PDOException("Missing POST parameters.");
		$statement = $db -> prepare("UPDATE `food&order` SET status = :status WHERE foodId = :foodId AND orderId = :orderId");
		$statement -> execute(array(":orderId" => $_POST["orderId"], ":foodId" => $_POST["foodId"], ":status" => $_POST["status"]));
		echo 0;
	}
	catch (PDOException $e)
	{
		echo 'ERROR: ' . $e -> getMessage();
        exit();
	}
?>
