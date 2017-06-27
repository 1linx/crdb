<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/session.php"); ?>

<?php

// get a list of all the artist rows and return it as array
function get_the_albums($artist_id) {
    global $connection;

    $query  = "SELECT * ";
    $query .= "FROM albums ";
    $query .= "WHERE ARTIST_ID = '" . $artist_id . "';";
    $albums = mysqli_query($connection, $query);

    confirm_query($albums);
     $albumsArray = array();
    while ($row = mysqli_fetch_assoc($albums)) {
      $albumsArray[$row["ID"]] = [$row["NAME"], $row["PIGID"]];
    };
    return $albumsArray;
}

$q = $_REQUEST["query"];
echo json_encode(get_the_albums($q));

?>
