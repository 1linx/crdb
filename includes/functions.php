<?php
// function to check that a database query actually returned a result.
	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed.");
		}
	}

// treat whatever is coming into the database with the utmost suspicion and purge its unclean soul
	function mysql_prep($unsafe_string) {
		global $connection;
		$escaped_string = mysqli_real_escape_string($connection, $unsafe_string);
		return $escaped_string;
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
		mysql_prep($value);
		$query  = "INSERT INTO codes ";
		$query .= "(CODE, ARTIST_ID, ALBUM_ID) ";
		$query .= "VALUES ('". $value . "', '" . $artistID . "', '" . $albumID . "')";
		$codeload = mysqli_query($connection, $query);

    confirm_query($codeload);
	}
	// return $file;
}

// get a list of Promo IDs
function get_PIDs() {
	global $connection;

	$query  = "SELECT * FROM PROMOIDS;";
	$find_PIDs = mysqli_query($connection, $query);

	confirm_query($find_PIDs);

	$pidsArray = array();
	 while ($row = mysqli_fetch_assoc($find_PIDs)) {
		 // echo $row["NAME"];
		 // $albumsArray[$row["ID"]] = $row["NAME"];
		 $pidsArray[$row["PID"]] = $row["NAME"];
	 };
	return $pidsArray;
}
