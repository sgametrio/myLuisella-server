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

    //Select table and then retrieve a JSON object
    try
    {
      $statement = $db -> query("SELECT * FROM `table`");
      $statement2 = $db -> prepare("SELECT orderId FROM `order` WHERE tableId = :tableId AND :tableId IN (SELECT DISTINCT tableId FROM `food&order`, `order` WHERE (status = 1 OR status = 2) AND `food&order`.orderId = order.orderId) ORDER BY orderedTime ASC");
        $statement3 = $db -> prepare("SELECT food.foodId, quantity, foodName, extraInfo, status FROM `food&order`, `food` WHERE food.foodId=`food&order`.foodId AND `orderId` = :orderId AND (status = 1 OR status = 2)");
      $json = array();
        $i = 0;
        while($row = $statement -> fetch())
        {
            $json[$i] = array(
                'tableName' => $row['tableName'],
                'tableNumber' => $row['tableNumber'],
                'tableId' => $row['tableId'],
                'customers' => $row['customers'],
            'order' => array()
            );
         $statement2 -> execute(array(":tableId" => $row['tableId']));
         $j = 0;
         while($row2 = $statement2 -> fetch())
         {
            $json[$i]["order"][$j] = array(
               'orderId' => $row2['orderId'],
               'foodIds' => array(),
               'quantities' => array(),
               'foodNames' => array(),
               'extraInfos' => array(),
               'status' => array()
            );
            $statement3 -> execute(array(":orderId" => $row2['orderId']));
            while($row3 = $statement3 -> fetch())
            {
               $json[$i]["order"][$j]["foodIds"][] = $row3["foodId"];
               $json[$i]["order"][$j]["quantities"][] = $row3["quantity"];
               $json[$i]["order"][$j]["foodNames"][] = $row3["foodName"];
               $json[$i]["order"][$j]["extraInfos"][] = $row3["extraInfo"];
               $json[$i]["order"][$j]["status"][] = $row3["status"];
            }
            $j++;
         }
            $i++;
        }
      if($i == 0)
            echo 0;
        $jsonstring = json_encode($json);
        echo $jsonstring;

    } catch (PDOException $e) {
        echo "Exception: " . $e -> getMessage();
    }
?>
