<?php
// function to check that a database query actually returned a result.
	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed.");
		}
	}

// get a specific artist. TBC.
function get_artist($artist_name) {
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM artists ";
		$query .= "WHERE NAME = '{$artist_name}' ";
		$query .= "LIMIT 1;";
		$artist = mysqli_query($connection, $query);

    confirm_query($artist);

		return mysqli_fetch_assoc($artist);

	}

// get a list of all the artist rows and return it as array
function get_artists() {
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM artists ";
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

// TBC will be used to narrow down album list according to selected artistId
// might shift this over to a JS function
function get_artist_album($artistId) {
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM albums ";
		$query .= "WHERE ARTIST_ID = \"" . $artistId . "\"";
		$albums = mysqli_query($connection, $query);

    confirm_query($albums);
     $artistsArray = array();
		while ($row = mysqli_fetch_assoc($albums)) {
      // echo $row["NAME"];
      $artistsArray[$row["ID"]] = $row["NAME"];
    };
    // return $artistsArray;

}

// get a list of all the albums rows and return it as array
function get_albums() {
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM albums ";
		$albums = mysqli_query($connection, $query);

    confirm_query($albums);
     $albumsArray = array();
		while ($row = mysqli_fetch_assoc($albums)) {
      // echo $row["NAME"];
      $albumsArray[$row["ID"]] = $row["NAME"];
    };
    return $albumsArray;

}

// get one unused code from an artist ID and an album ID, then delete it.
function get_unused_code($artistID, $albumID) {
	global $connection;

	// Get the code from the database, stores in variable
	$query  = "SELECT ID, CODE ";
	$query .= "FROM codes ";
	$query .= "WHERE ARTIST_ID = " . $artistID;
	$query .= " AND ALBUM_ID = " . $albumID;
	$query .= " LIMIT 1";
	$code = mysqli_query($connection, $query);

	confirm_query($code);

	$code_array = mysqli_fetch_assoc($code);

	// deletes the row from the database once code has been retrieved
	$delete_query =  "DELETE FROM codes ";
	$delete_query .= "WHERE ID = " . $code_array["ID"];
	$delete_query .= " LIMIT 1";

	$code = mysqli_query($connection, $delete_query);

	confirm_query($delete_query);

	return $code_array["CODE"];
}

// build the form for selecting an artist and an album
function build_artist_album_selectors() {
	$output = '<form action="./index.php" id="codesearchForm" method="get">';
	$output .= 	'<select name="artist">';
	$output .= 	  '<option value="" disabled="disabled" selected="selected">Please select a name</option>';

  $count = 1;
  foreach (get_artists() as $key => $value) {
    $output .= '<option value="' . $key .'">' . $value . '</option>';
    $count++;
  }


	$output .= '</select>';
	$output .= '<select  name="album">';
	$output .= 	  '<option value="" disabled="disabled" selected="selected">Please select an album</option>';

  $count = 1;
  foreach (get_albums() as $key => $value) {
    $output .= '<option value="' . $key .'">' . $value . '</option>';
    $count++;
  };
	$output .=  '</select>';
  $output .= '<input type="submit" class="btn" value="Submit">';
	$output .= '</form>';

	return $output;
}

// insert codes into database from text file
function load_codes_from_text_file($artistID, $albumID) {
	global $connection;

	// print_r($_FILES["codefile"]);
	// echo $_FILES["codefile"]["tmp_name"];

	$file = file_get_contents($_FILES["codefile"]["tmp_name"]);
	// $file = mysqli_real_escape_string($connection, $file);
	preg_match_all("([a-zA-Z0-9]{9})" ,$file, $matches );

	// print_r($matches);

	foreach ($matches["0"] as $key => $value) {
		// echo $value;
		$query  = "INSERT INTO codes ";
		$query .= "(CODE, ARTIST_ID, ALBUM_ID) ";
		$query .= "VALUES ('". $value . "', '" . $artistID . "', '" . $albumID . "')";
		// echo $query;
		$codeload = mysqli_query($connection, $query);

    confirm_query($codeload);
	}
	// return $file;
}
