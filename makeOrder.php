<?php
//uncomment this if you don't handle ORIGIN in configuration file
	header("Access-Control-Allow-Origin: *");
    	header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    require_once('./globals.php'); 
	$ajaxObject = new stdClass();
	$i = 0;
	$ajaxObject -> response_error = array();
    // Connection to DB
    try
    {
        $db = new PDO('mysql:host='.$DB_host.';dbname='.$DB_name.';charset=utf8', $DB_user, $DB_password);
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
		$ajaxObject -> result = "Bad";
		$ajaxObject -> response_error[$i] = new stdClass();
		$ajaxObject -> response_error[$i] -> text = $e -> getMessage();
		$i++;
		echo json_encode($ajaxObject);
        exit();
    }
    //Inserting new plate into DB
    try
    {
		/*
		* 1) retrieve data from JSON passed to this page.
		* 2) Query, verify that ingredients of ordered plates are enough and order plates for specified quantity (or possible maximum quantity).
		* 3) Create response_error for every plate and send it via JSON.
		*/
		if(isset($_POST['order']))
		{ 
			/*
             * CHANGED LOGIC, REMOVED INGREDIENTS' PART
             
            $db -> exec('set autocommit=0');
			$db -> exec('START TRANSACTION');
             */
			$orderObject = json_decode($_POST['order']);
			$statement = $db -> prepare("INSERT INTO `luisella`.`order` (`tableId`, `waitressId`) VALUES (:tableId, :waitressId)");
			$statement -> execute(array(":tableId" => $orderObject -> tableId, ":waitressId" => $orderObject -> waitressId));
			$orderId = $db -> lastInsertId();
			//$remaining_statement = $db -> prepare("SELECT remaining FROM `ingredient` WHERE `ingredientId` = :ingredientId FOR UPDATE");
			//$found_ingredient_statement = $db -> prepare("SELECT ingredientId, quantity FROM `ingredient&food` WHERE `foodId` = :foodId");
			
			foreach($orderObject->food as $value)
			{
				/*
                $found_ingredient_statement -> execute(array(":foodId" => $value -> foodId));
				$quantity = array();
				$ingredients = array();
				$maximum_quantity = $value -> quantity;
				while($row = $found_ingredient_statement -> fetch())
				{
					$quantity[] = $row['quantity'];
					$ingredients[] = $row['ingredientId'];
					$remaining_statement -> execute(array(":ingredientId" => $row['ingredientId']));
					//function which determines maximum quantity possible to do (the best is quantity passed via AJAX).
					//every time I calculate the maximum for plate based on remaining/dose. if it's minor than maximum_quantity I update it.
					while($row2 = $remaining_statement -> fetch())
					{
						if($maximum_quantity > $row2['remaining']/$row['quantity'])
						{
							$maximum_quantity = $row2['remaining']/$row['quantity'];
							//we assure that maximum_quantity is an integer because division by two integer in PHP returns a float
							$maximum_quantity = (int)$maximum_quantity;
						}
					}
				}
				if($maximum_quantity > 0)
				{
                */
					//insert links between food&order
					$insert_order_statement = $db -> prepare("INSERT INTO `food&order` (`foodId`, `orderId`, `quantity`) VALUES (:foodId, $orderId, :quantity)");
					$insert_order_statement -> execute(array(":foodId" => $value -> foodId, ":quantity" => $value -> quantity));
					
					//now update remaining quantity based on $maximum_quantity * quantity
					//$update_remaining_statement = $db -> prepare("UPDATE `ingredient` SET `remaining` = `remaining` - :calculated WHERE `ingredientId` = :ingredientId");
					/*
                    $c = 0;
					foreach($quantity as $value2)
					{
						$calculated_remaining = $value2 * $maximum_quantity; 
						$update_remaining_statement -> execute(array(":calculated" => $calculated_remaining, ":ingredientId" => $ingredients[$c]));
						$c++;
					}
					//now returns feedback to the user
					
				}
				//echo $maximum_quantity." < ".$value -> quantity;
				if($maximum_quantity < $value -> quantity)
				{
					$report_feedback_statement = $db -> prepare("SELECT foodName FROM `food` WHERE `foodId` = :foodId");
					$report_feedback_statement -> execute(array(":foodId" => $value -> foodId));
					while($row3 = $report_feedback_statement -> fetch())
					{
						$not_inserted = ($value -> quantity - $maximum_quantity);
						$ajaxObject -> response_error[$i] = new stdClass();
						
						$ajaxObject -> response_error[$i] -> text = "Not inserted ".$not_inserted." plate of ".$row3['foodName'];
						$i++;
					}
				}
				*/
				
			}
			//$db -> exec('COMMIT');
			//$db -> exec('UNLOCK TABLES');
		}
		else
		{
			throw new PDOException("Missing parameter.");
		}
        //If an exception isn't thrown then it's time to echo something!
        $ajaxObject -> result = "Good";
		echo json_encode($ajaxObject);
    } catch (PDOException $e) {
		//$db -> exec('ROLLBACK');
		//$db -> exec('UNLOCK TABLES');
        $ajaxObject -> result = "Bad";
		$ajaxObject -> response_error[$i] = new stdClass();
		$ajaxObject -> response_error[$i] -> text = $e -> getMessage();
		$i++;
		echo json_encode($ajaxObject);
    }
?>
