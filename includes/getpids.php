<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/session.php"); ?>

<?php

// get a list of all the artist rows and return it as array
function get_the_promo_ids($promo_id) {
    global $connection;

    $query  = "SELECT * ";
    $query .= "FROM promoids ";
    $query .= "WHERE ID = ";
    $query .= "(SELECT PROMO_ID FROM albums WHERE ID = " . $promo_id . ") ";
    $query .= "LIMIT 1;";
    $promoids = mysqli_query($connection, $query);

    confirm_query($promoids);
     $promoidsArray = array();
    while ($row = mysqli_fetch_assoc($promoids)) {
      // echo $row["NAME"];
      // $albumsArray[$row["ID"]] = $row["NAME"];
      $promoidsArray[$row["PID"]] = $row["NAME"];
    };
    // print_r($albumsArray);
    return $promoidsArray;
}

$promo_id = $_REQUEST["query"];

echo json_encode(get_the_promo_ids($promo_id));
// print_r(get_the_promo_ids($promo_id));

?>
