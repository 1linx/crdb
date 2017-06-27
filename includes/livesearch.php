<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/session.php"); ?>

<?php

$q=$_GET["q"];

// make sure all the input data is clean
$q= mysql_prep($q);


function find_artists($input) {
  global $connection;

  $query  = "SELECT ID, NAME from artists ";
  $query .= "WHERE NAME LIKE '%". $input . "%';";
  $artistFind = mysqli_query($connection, $query);
  confirm_query($artistFind);
  if (mysqli_num_rows($artistFind) == 0) {
    return "Nothing found";
  } else {
     $resultsArray = array();
     while ($row = mysqli_fetch_assoc($artistFind)) {
       // echo $row["NAME"];
       // $albumsArray[$row["ID"]] = $row["NAME"];
       $resultsArray[$row["ID"]] = $row["NAME"];
     };
     // print_r($albumsArray);
     return $resultsArray;
  }

}


//output the response
echo json_encode(find_artists($q));
?>
