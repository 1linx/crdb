<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/session.php"); ?>

<?php

// get a list of all the artist rows and return it as array
function get_the_artists() {
		// echo "Test";
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM artists ;";
		// $query .= "LIMIT 1000;";
		$artists = mysqli_query($connection, $query);

    confirm_query($artists);
     $artistsArray = array();
		while ($row = mysqli_fetch_assoc($artists)) {
      // echo $row["NAME"];
      $artistsArray[$row["ID"]] = $row["NAME"];
    };
    return $artistsArray;

}

echo json_encode(get_the_artists());

?>
