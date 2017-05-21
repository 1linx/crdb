<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.inc"); ?>

<?php


// if (isset($_GET["artist"]) && isset($_GET["artist"])) {
//   echo $_GET["artist"];
//   echo $_GET["album"];
//
//   $artistName = get_artist($_GET["artist"]);
//
//   print_r($artistName["NAME"]);
//
// }

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h1>Get a code</h1>
<?php
// if (isset($_GET["artist"]) && isset($_GET["album"])) {
//   echo $_GET["artist"];
//   echo $_GET["album"];
//
// 	echo get_unused_code($_GET["artist"], $_GET["album"]);
// }
if (isset($_GET["artist"]) && isset($_GET["album"])) {

	$unused_code = get_unused_code($_GET["artist"], $_GET["album"]) ;
	echo $unused_code;

} else {
	echo build_artist_album_selectors();
}
?>

<form action="./index.php" method="post" enctype="multipart/form-data">
  Send these files:<br />
  <input name="codefile" type="file" /><br />
  <input type="submit" value="Send file" />
</form>

<?php if (isset($_FILES["codefile"])) {
	load_codes_from_text_file(1,1);
}
?>


</body>
</html>
