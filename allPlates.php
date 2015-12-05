<?php
   //uncomment this if you don't handle ORIGIN in configuration file
   header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

    require('./globals.php');

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
      $statement = $db -> query("SELECT foodId, foodName, todayMenu, category FROM `food`");
      $json = array();
        $i = 0;
        while($row = $statement -> fetch())
        {
            $json[$i] = array(
                'foodName' => $row['foodName'],
                'todayMenu' => $row['todayMenu'],
                'category' => $row['category'],
                'foodId' => $row['foodId']
            );
            $i++;
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;

    } catch (PDOException $e) {
        echo "Exception: " . $e -> getMessage();
    }
?>
