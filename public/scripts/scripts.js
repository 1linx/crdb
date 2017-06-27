if (document.getElementById('codeFinderForm')) {

	var codeFinderForm = document.getElementById('codeFinderForm');
	codeFinderForm = codeFinderForm.getElementsByTagName('select');

	// AJAX request to get list of Artists
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					//get the artists data as a JSON
					var artistsArray = JSON.parse(this.responseText);
					var select = document.getElementById("codeFinderForm").getElementsByTagName('select');

					//insert blank option at start
					var defaultOptionSelect = document.createElement('option');
					defaultOptionSelect.setAttribute("disabled", "disabled");
					defaultOptionSelect.setAttribute("selected", "selected");
					defaultOptionSelect.innerHTML = "Please select an artist";
					select[0].appendChild(defaultOptionSelect);

					for (var key in artistsArray) {
						var opt = document.createElement('option');
				    opt.value = key;
				    opt.innerHTML = artistsArray[key];
				    select[0].appendChild(opt)
					}

			}
	};
	xmlhttp.open("GET", "../includes/getartists.php", true);
	xmlhttp.send();

	function get_albums(str) {

		clearSpans("codeFinderForm");

		var codeFinderForm = document.getElementById('codeFinderForm');
		var select = document.createElement('select');
		select.setAttribute("name", "album");
		select.setAttribute("onchange", "get_pids(this.options[this.selectedIndex].value)");

		//prevent adding more child nodes by removing the last one each time new select option picked,
		//only if there IS one present tho
		var codeFinderSelect = document.getElementById('codeFinderForm').getElementsByTagName('select');
		if (typeof codeFinderSelect[1] != 'undefined') {
			codeFinderForm.removeChild(codeFinderForm.getElementsByTagName('select')[1]);
		}

		codeFinderForm.insertBefore(select, document.getElementById('codeFinderForm').getElementsByTagName('input')[0]);

		// AJAX request to get list of Albums, limited by selected artist
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
						//get the albums data as a JSON
						var albumsArray = JSON.parse(this.responseText);
						var select = document.getElementById("codeFinderForm").getElementsByTagName('select');
						var opt = document.createElement('option');
						opt.value = 0;
						opt.disabled = "disabled";
						opt.selected = "selected";
						opt.innerHTML = "Select an album";
						select[1].appendChild(opt)

						for (var key in albumsArray) {
							var opt = document.createElement('option');
							opt.value = key;
							opt.setAttribute("pigid", albumsArray[key][1]);
							opt.innerHTML = albumsArray[key][1] + ": " + albumsArray[key][0];
							select[1].appendChild(opt)
						}

				}
		};
	    xmlhttp.open("GET", "../includes/getalbums.php?query=" + str, true);
	    xmlhttp.send();
	}

		function get_pids(str) {
			clearSpans("codeFinderForm");

			// AJAX request to get PID for the selected album
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
							//get the albums data as a JSON
							console.log(this.responseText);
							var pidsArray = JSON.parse(this.responseText);
							var select = document.getElementById("codeFinderForm").getElementsByTagName('select');

							for (var key in pidsArray) {
								var span = document.createElement('span');
								span.setAttribute("id", "pid");
								span.setAttribute("key", key);
								span.innerHTML = pidsArray[key];
								var codeFinderForm = document.getElementById('codeFinderForm');
								codeFinderForm.insertBefore(span, document.getElementById('codeFinderForm').getElementsByTagName('input')[0]);
							}

					}
			};
		    xmlhttp.open("GET", "../includes/getpids.php?query=" + str, true);
		    xmlhttp.send();
		}

}

// Live search of artist details
function showResult(str) {
  if (str.length==0) {
    document.getElementById("searchResults").innerHTML="";
    document.getElementById("searchResults").style.border="0px";
    return;
  }
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
			//first check that it's not a "nothing found" message coming back
			if (typeof JSON.parse(this.responseText) != "string") {

				var foundArtists = JSON.parse(this.responseText);

				//clear out the old messages to prevent it gungeing up
				document.getElementById("searchResults").innerHTML="";

				for (var key in foundArtists) {
					//append data to the span beside text box
					document.getElementById("searchResults").innerHTML += "<span id='" + key + "' onclick='populateSearchBox(" + key + ")'>" + foundArtists[key] + "</span>";
					document.getElementById(key).style.textDecoration="underline";
					document.getElementById(key).style.color="blue";
					document.getElementById(key).style.cursor="pointer";
					// make it prettier if more than one result returned
					if (Object.keys(foundArtists).length > 1) {
						document.getElementById("searchResults").innerHTML += ", ";
					}
				}
			//if there are no results, provide this message
			} else {
				document.getElementById("searchResults").innerHTML = "No artist found";
			};
    }
  }
  xmlhttp.open("GET","../includes/livesearch.php?q="+str,true);
  xmlhttp.send();
}

function get_pigs(str) {
		var enteredPig = document.getElementById("pigBox").value;
		console.log(enteredPig);

		// AJAX request to get PID for the selected album
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
						//get the albums data as a JSON
						console.log(this.responseText);
						var pigsArray = JSON.parse(this.responseText);
						console.log(enteredPig);

						window.location = "./?artist=" + pigsArray[enteredPig]["artistId"] + "&album=" + pigsArray[enteredPig]["albumId"];
				}
		};
			xmlhttp.open("GET", "../includes/getpigs.php?query=" + str, true);
			xmlhttp.send();
		}



// main Insert AJAX function
function postCodes(artistName, albumName, promoId, pigId, unusedCodes) {
	var inputData = "artistName=" + artistName + "&albumName=" + albumName + "&promoId=" + promoId + "&pigId=" + pigId + "&unusedCodes=" + unusedCodes;
	console.log(inputData);
	// AJAX request to post codes and add artist / album as required
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
					var return_data = xmlhttp.responseText;
					// TODO: Need some means of feedback for when successfully entered.
					console.log(return_data);

			}
	};
		xmlhttp.open("POST", "../includes/codeprocessing.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xmlhttp.send(inputData);
}


function clearSpans(str) {
	var targetElement = document.getElementById(str);

	//prevent adding more child nodes by removing the last one each time new select option picked,
	//only if there IS one present tho
	var targetSpan = document.getElementById(str).getElementsByTagName('span');
	console.log(targetSpan);
	if (typeof targetSpan[0] != 'undefined') {
		targetElement.removeChild(targetElement.getElementsByTagName('span')[0]);
	}
}
