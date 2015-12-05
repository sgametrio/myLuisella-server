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
      if(isset($_GET['tableId']) && $_GET['tableId'] != "all")
      {
         $statement = $db -> prepare("SELECT * FROM `table` WHERE tableId=:tableId");
         $statement -> execute(array(":tableId" => $_GET['tableId']));
      }
      else
         $statement = $db -> query("SELECT * FROM `table`");
        $json = array();
        $i = 0;
        while($row = $statement -> fetch())
        {
            $json[$i] = array(
                'tableName' => $row['tableName'],
                'tableNumber' => $row['tableNumber'],
                'tableId' => $row['tableId'],
                'customers' => $row['customers']
            );
            $i++;
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;

    } catch (PDOException $e) {
        echo "Exception: " . $e -> getMessage();
    }
?>
