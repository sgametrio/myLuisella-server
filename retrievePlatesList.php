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
    }
    catch (PDOException $e)
    {
        echo 'ERROR: ' . $e -> getMessage();
        exit();
    }
    
    try
    {
		// dessert have different ingredient story..
		$statement = $db -> query("SELECT foodId, foodName FROM luisella.food WHERE category <> 'dessert' ORDER BY category DESC");
        $json = array();
        $i = 0;
        while($row = $statement -> fetch())
        {
            $json[$i] = array(
                'foodId' => $row['foodId'],
				'foodName' => $row['foodName']
            );
            $i++;
        }
		if($i == 0)
			echo "No categories.";
		else
		{
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
    } catch (PDOException $e) {
        echo "Exception: " . $e -> getMessage();
    }
?>
