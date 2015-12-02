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

    try
    {
		if(!isset($_POST['foodIds']))
			throw new PDOException("Missing parameter.");
        //Prepare INSERT query
		$statement = $db -> prepare("UPDATE `luisella`.`food` SET `todayMenu` = :todayMenu WHERE `foodId` = :foodId");
		$foodObject = json_decode($_POST['foodIds']);
		foreach($foodObject as $obj)
			$statement -> execute(array(':todayMenu' => $obj -> checked, ':foodId' => $obj -> foodId));
        echo "0";
    } catch (PDOException $e) {
        echo 'Exception: ' . $e -> getMessage();
    }
?>
