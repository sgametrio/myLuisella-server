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

    //Select food that have todayMenu attribute set on 1 (true) and then retrieve a JSON object
    try
    {
        $statement = $db -> query("SELECT * FROM `food` WHERE `todayMenu` = 1");
        $json = array();
        $i = 0;
        while($row = $statement -> fetch())
        {
            $json[$i] = array(
                'foodId' => $row['foodId'],
                'foodName' => $row['foodName'],
            'description' => $row['description']
            );
            $i = $i+1;
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;

    } catch (PDOException $e) {
        echo "Exception: " . $e -> getMessage();
    }
?>
