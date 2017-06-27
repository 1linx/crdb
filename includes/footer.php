</body>
<script src="./scripts/scripts.js"></script>
</html>

<?php
if (isset($_SESSION["message"])) {
	// clear message after use.
	$_SESSION["message"] = null;
}


// kill db connection
if (isset($connection)) {
  mysqli_close($connection);
}
?>
