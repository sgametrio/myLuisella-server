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
    //Inserting new plate into DB
    try
    {
		//TODO: Insert into ingredient&food table links between food and ingredients.
        //Prepare INSERT query
        $statement = $db -> prepare("INSERT INTO `luisella`.`food` (`foodName`, `price`, `description`, `category`) VALUES (:foodName, :price, :description, :category)");
        $statement -> execute(array(':foodName' => $_POST['foodName'],
                                    ':price' => $_POST['price'],
                                    ':description' => $_POST['description'],
                                    ':category' => $_POST['category']));
        //If an exception isn't thrown then it's time to echo something!
        echo "Good";
    } catch (PDOException $e) {
        echo 'Exception: ' . $e -> getMessage();
    }
?>
