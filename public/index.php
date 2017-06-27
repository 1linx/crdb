<?php require_once("../includes/header.php"); ?>

	<h1>Code Redemption support tools</h1>
	<?php
	// Display code and display code upload button if URL parameters set to artist and album.
	if (isset($_GET["artist"]) && isset($_GET["album"])) {
		// TODO Show album details
	  echo "<h2>Unused code:</h2>";
		$unused_code = get_unused_code($_GET["artist"], $_GET["album"]) ;
		echo $unused_code;
		echo "<h2>Add more codes</h2>";
	?>
	<form action="./" method="post" enctype="multipart/form-data">
	  Send these files:<br />
		<input type="hidden" name="artist_id" value=<?php echo $_GET["artist"] ?> />
		<input type="hidden" name="album_id" value=<?php echo $_GET["album"] ?> />
	  <input name="codefile" type="file" /><br />
	  <input type="submit" value="Send file" />
	</form>
	<?php
	}
	?>
	<?php
	// if files are uploaded, enter them into database
	if (isset($_FILES["codefile"])) {
		load_codes_from_text_file($_POST["artist_id"],$_POST["album_id"]);
	}
	?>

	<?php
	// if nothing is set, show code finder tools
	if (!isset($_GET["artist"]) && !isset($_GET["album"])) {

	?>
	<div id="codeFinder">
		<form action="./" id="codeFinderForm" method="get">

			<select name="artist" onchange="get_albums(this.options[this.selectedIndex].value)">
				<!-- All this loaded by AJAX -->
			</select>
			<input type="submit" class="btn" value="Submit">
		</form>

	</div>

	<div id="pigFinder">
		  <input type="text" size="4" id="pigBox" name="pigID" placeholder="" required>
			<input type="submit" id="pigFinderSubmit" class="btn" value="Find PIG ID" onclick="get_pigs(document.getElementById('pigBox').value)">
	</div>
	<?php
	}
	?>

	<h2>Add new codes</h2>
	<form action="./entercodes.php">
    <input type="submit" value="Enter codes" />
</form>


<?php require_once("../includes/footer.php"); ?>
