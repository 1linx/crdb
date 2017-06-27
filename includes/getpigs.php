<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/session.php"); ?>

<?php

// get a list of all the artist rows and return it as array
function get_full_album_details($pigId) {
		// echo "Test";
		global $connection;

		$query  = "SELECT albums.NAME as 'albumName', albums.ID as 'albumId', albums.PIGID, artists.NAME as 'artistName', artists.ID as 'artistId', promoids.NAME as 'pid' ";
    $query .= "from albums INNER JOIN artists on albums.ARTIST_ID = artists.ID ";
    $query .= "INNER JOIN promoids on albums.PROMO_ID = promoids.ID ";
		$query .= "WHERE PIGID = " . $pigId . " ";
		$query .= "LIMIT 1;";
		// $query .= "LIMIT 1000;";
		$albumDetails = mysqli_query($connection, $query);

    confirm_query($albumDetails);
     $albumDetailsArray = array();
		while ($row = mysqli_fetch_assoc($albumDetails)) {
      $albumDetailsArray = array(
        $row["PIGID"] => array(
          "artistName" => $row["artistName"],
          "artistId" => $row["artistId"],
          "albumName" => $row["albumName"],
          "albumId" => $row["albumId"],
          "pid" => $row["pid"]
        )
      );
    };
    return $albumDetailsArray;

}
$q = $_REQUEST["query"];
echo json_encode(get_full_album_details($q));

?>
