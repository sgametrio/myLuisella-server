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

    //Select orders and then retrieve a JSON object

    try
    {
      if(!isset($_POST['tableId']))
         throw new PDOException("Missing POST parameters.");
        //Selecting orders of a defined table that have to be made.
        $statement = $db -> prepare("SELECT orderId FROM `order` WHERE tableId = :tableId AND :tableId IN (SELECT DISTINCT tableId FROM `food&order`, `order` WHERE (status = 1 OR status = 2) AND `food&order`.orderId = order.orderId ORDER BY orderedTime DESC)");
        //Check if POSTED data exists
      $statement -> execute(array(':tableId' => $_POST['tableId']));
        $json = array();
        $i = 0;
      $statement2 = $db -> prepare("SELECT food.foodId, quantity, foodName, extraInfo, status FROM `food&order`, `food` WHERE food.foodId=`food&order`.foodId AND (status = 1 OR status = 2) AND `orderId` = :orderId");
        //Send only HOT information as: orderId (necessary for modifying, deleting or delivering), orderedTime.
        while($row = $statement -> fetch())
        {
            //Creating array on each position of my json array that has to be encoded and then retrieved.
         //Each order may contain multiple plates
            $json[$i] = array(
                'orderId' => $row['orderId'],
            'foodIds' => array(),
            'quantities' => array(),
            'extraInfos' => array(),
            'foodNames' => array(),
            'status' => array()
            );
         //execute query that retrieve foodId, quantity and extraInfo of every plate with orderId
         $statement2 -> execute(array(":orderId" => $row['orderId']));
         while($row2 = $statement2 -> fetch())
         {
            $json[$i]["foodIds"][] = $row2["foodId"];
            $json[$i]["quantities"][] = $row2["quantity"];
            $json[$i]["foodNames"][] = $row2["foodName"];
            $json[$i]["extraInfos"][] = $row2["extraInfo"];
            $json[$i]["status"][] = $row2["status"];
         }
            $i = $i+1;
        }
        //Means that there aren't any order.
        if($i == 0)
            echo 0;
        else
        {
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }

    } catch (PDOException $e) {
        echo "Exception: " . $e -> getMessage();
    }
?>
