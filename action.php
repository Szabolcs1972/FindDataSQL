<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <title>Keresés SQL Adatbázisban</title>

  

</head>

<body class="container">

<?php
  $hit = 0;
  $error = "There is no connection to the database!<br>";
  $tables = array();
  $success = 0;

  if (isset($_POST['question'])) {
    $question = htmlspecialchars($_POST['question']);
  }

  if (isset($_POST['user']) && $_POST['user'] !=="") {
    $user = $_POST['user'];
  } else {
    $error .= "Bad username!<br>";
  }

  if (isset($_POST['pw'])) {
  $pw = $_POST['pw'];
  } else {
    $error .= "Bad password!<br>";
  }


  if (isset($_POST['database']) && $_POST['database'] !=="") {
  $database = $_POST['database'];
  } else {
    $error .= "Bad database name!<br>";
  }


  if (isset($user) && isset($pw) && isset($database)) {

      $connection = new mysqli();

      $connection -> connect("localhost", $user, $pw, $database);

      $connection->set_charset('utf8');

      $queryTables = $connection->query("show tables;");


        foreach ($queryTables as $row => $value) {
          //print_r($row);

          foreach ($value as $element) {
            $tables[] = $element;
            //print "<p>".$element."</p>";
          }
        }

        //print_r($tables);

        $columns = array();

        foreach ($tables as $table) {

          $columnNames = $connection->query("show columns FROM `" . $table . "`;");

          //print "<p>Helló 2!</p>";
          //print_r($columnNames);

          //$index=1;

          foreach ($columnNames as $row => $column) {


            //print_r($value);
            //print "<br>";
            //print "<p>".$value['current_field']."</p>";

            /*
            for($index;$index<10;$index++){
            //print_r($value['Field']);
            print "<p>".$value['Field']."</p>";
            }
            */
            $columns[] = $column['Field'];
            $search = $connection->query("SELECT * FROM `" . $table . "` WHERE `" . $column['Field'] . "` = '" . $question . "';");
            //print_r($search);
            //print "<br>";

            foreach ($search as $searchrow => $value) {
              //print_r($value);
              if (array_search($question, $value)) {
                $hit = 1;
                print "<h1>Success!</h1>";
                print "<p>The data: <strong>" . $question . "</strong> has been found in the database!</p>";
                print "<p>SQL table name: " . $table . "</p>";
                print "<p>Column name: " . $column['Field'] . "</p>";
              }
            }

            //print "<p>".$value['Field']."</p>";
            /*
            foreach($value as $element){

              //print "<p>".$element."</p>";
              print "<p>".$value['Field']."</p>";
          }
          */

            //$index++;

          }

        }
        if ($hit == 0) {
          print "<h1>Failed!</h1>";
          print "<p>The data: <strong>" . $question . "</strong> is not in the database!</p>";
        }
        $connection ->close();
        //print_r($columns);


    } else {
    print "<p>".$error."</p>";
    }
      ?>

</body>

</html>