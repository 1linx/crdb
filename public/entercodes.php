<?php require_once("../includes/header.php"); ?>

<div id="codeEntryForm">

  Artist name:<br>
  <input type="text" size="25" id="artistNameSearchBox" name="artistName" placeholder="e.g. Nirvana" onkeyup="showResult(this.value)" required>
	<span id="searchResults"></span>
  <br>
  Album name:<br>
  <input type="text" size="25" id="albumNameSearchBox" name="albumName" placeholder="e.g. Nevermind" required>
  <br>
  <br>
  Promotion Incentive Group:<br>
  <input type="text" size="4" id="pigBox" name="pigID" placeholder="" required>
  <br>
  <br>
  Promotion ID: <br>
  <select id="promoIdSelector" name="promoID">
    <option disabled="disabled" selected="selected">Selected a Promo ID</option>
    <?php
    $found_PIDs = get_PIDs();
    foreach ($found_PIDs as $key => $value) {
      echo "<option value=\"" . $key . "\">" . $value . "</option>";
    }
    ?>
  </select>
  <br>
  <br>
  Paste codes:<br>
  <textarea cols="20" rows="25" id="unusedCodesBox" name="unusedCodes">e.g. A12GHTX47</textarea>
  <br>
  <br>
  <br>
  <br>
  <input id="submitBtn" type="submit" value="Submit">

</div>

<div>
Upload codes:<br>
<input name="codefile"  id="unusedCodesFiles" type="file" />
</div>

</body>
<script>
var textbox = document.getElementById("unusedCodesBox");
textbox.addEventListener('focus', function() {
	textbox.innerHTML = "";
});

function populateSearchBox(thisId) {
	var artistNameSearchBox = document.getElementById("artistNameSearchBox");
	artistNameSearchBox.value = document.getElementById(thisId).innerHTML;
	searchResults.innerHTML = "";

}

document.getElementById("submitBtn").onclick = function(){
  var artistNameInput = document.getElementById("artistNameSearchBox").value;
  // console.log(artistNameInput);
  var albumNameInput = document.getElementById("albumNameSearchBox").value;
  // console.log(albumNameInput);
  var promoIdInput = document.getElementById("promoIdSelector").value;
  // console.log(promoIdInput);
  var pigInput = document.getElementById("pigBox").value;
  // console.log(pigInput);
  var unusedCodesInput = document.getElementById("unusedCodesBox").value;
  // console.log(unusedCodesInput);
  var fileSelect = document.getElementById('unusedCodesFiles').files;
  // console.log(fileSelect);

  postCodes(artistNameInput, albumNameInput, promoIdInput, pigInput, unusedCodesInput);
  window.location = "./";
}


</script>
</html>
