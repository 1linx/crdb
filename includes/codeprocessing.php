<?php require_once("../includes/db_connection.inc"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/session.php"); ?>

<?php

// make sure all the input data is clean
$artistName = mysql_prep($_POST["artistName"]);
$albumName = mysql_prep($_POST["albumName"]);
$promoId = mysql_prep($_POST["promoId"]);
preg_match_all("([a-zA-Z0-9]{9})", $_POST["unusedCodes"], $codeMatches );

foreach ($codeMatches["0"] as $key => $value) {
  $unusedCodes[$key] = mysql_prep($value);
}


function load_new_codes($artistName, $albumName, $promoId, $unusedCodes) {
  global $connection;

  // check whether artist exists
  $query  = "SELECT ID, NAME from artists ";
  $query .= "WHERE NAME LIKE '%". $artistName . "%';";
  $artistFind = mysqli_query($connection, $query);
  confirm_query($artistFind);

  // if artist name search returns nothing then insert artist and album:
    if (mysqli_num_rows($artistFind) == 0) {

      $query  = "INSERT INTO artists (NAME)";
      $query .= "VALUES('" . $artistName . "');";
      $artistInsert = mysqli_query($connection, $query);
      confirm_query($artistInsert);

      // printf("Last inserted record has id %d\n", mysqli_insert_id($connection));
      $query = "INSERT INTO albums (name, ARTIST_ID, PROMO_ID)";
      $query .= "VALUES('" . $albumName . "', LAST_INSERT_ID(), (SELECT ID FROM promoids WHERE PID = " . $promoId . "));";
      $albumInsert = mysqli_query($connection, $query);
      confirm_query($albumInsert);

      foreach ($unusedCodes as $key => $value) {
        $query  = "INSERT INTO codes ";
        $query .= "(CODE, ARTIST_ID, ALBUM_ID) ";
        $query .= "VALUES ('". $value . "', (SELECT ID FROM artists WHERE name = '" . $artistName . "' LIMIT 1), ";
        $query .= "(SELECT ID FROM albums WHERE name = '" . $albumName . "' LIMIT 1))";
        $codeload = mysqli_query($connection, $query);

        confirm_query($codeload);
      }
      $_SESSION["message"] = "Codes loaded successfully (new artist, new album): " . $artistName . " - " . $albumName . ", " . count($unusedCodes) . " codes added.";

    // otherwise, if artist found, insert the album.
    } elseif (mysqli_num_rows($artistFind) == 1) {

        // echo "Found 1";
        $foundArtistArray = (mysqli_fetch_assoc($artistFind));

        //check whether album already exists
        $query  = "SELECT ID, NAME from albums ";
        $query .= "WHERE ARTIST_ID = " . $foundArtistArray['ID'] . " AND NAME = '" . $albumName . "';";
        $albumFind = mysqli_query($connection, $query);
        confirm_query($albumFind);
        echo mysqli_num_rows($albumFind) . " rows found";
        echo "<br>";


        //if album found, add codes to existing artist/album
        if(mysqli_num_rows($albumFind) == 1) {
          echo "Firing this <br>";
          $foundAlbumArray = (mysqli_fetch_assoc($albumFind));
          echo $foundAlbumArray['ID'];
          echo $foundArtistArray['ID'];
          foreach ($unusedCodes as $key => $value) {
            $query  = "INSERT INTO codes ";
            $query .= "(CODE, ARTIST_ID, ALBUM_ID) ";
            $query .= "VALUES ('". $value . "', '" . $foundArtistArray['ID'] . "', '" . $foundAlbumArray['ID'] . "');";
            $codeload = mysqli_query($connection, $query);
            echo "<br>";
            echo "<br>";
            echo $query;
            echo "<br>";

            echo "this";
            confirm_query($codeload);
          }
        $_SESSION["message"] = "Codes loaded successfully (existing artist, existing album): " . $foundArtistArray['NAME'] . " - " . $foundAlbumArray['NAME'] . ", " . count($unusedCodes) . " codes added.";

        //otherwise, add a new album and codes
        } elseif (mysqli_num_rows($albumFind) == 0) {
          $query = "INSERT INTO albums (name, ARTIST_ID, PROMO_ID)";
          $query .= "VALUES('" . $albumName . "', " . $foundArtistArray['ID'] . ", (SELECT ID FROM promoids WHERE PID = " . $promoId . "));";
          $albumInsert = mysqli_query($connection, $query);
          confirm_query($albumInsert);
          $newAlbumId = mysqli_query($connection, "SELECT LAST_INSERT_ID();");
          $newAlbumId = mysqli_fetch_row($newAlbumId);
          foreach ($unusedCodes as $key => $value) {
            $query  = "INSERT INTO codes ";
            $query .= "(CODE, ARTIST_ID, ALBUM_ID) ";
            $query .= "VALUES ('". $value . "', '" . $foundArtistArray['ID'] . "', '" .  $newAlbumId[0] . "');";
            $codeload = mysqli_query($connection, $query);
            confirm_query($codeload);
          }
          $_SESSION["message"] = "Codes loaded successfully (existing artist: " . $foundArtistArray['ID'] . ", new album: " .  $newAlbumId[0]  . "): " . $foundArtistArray['NAME'] . " - " . $albumName . ", " . count($unusedCodes) . " codes added.";
        }

    } elseif (mysqli_num_rows($artistFind) > 1) {
      // TODO: what if more than one comes back?
      $_SESSION["message"] = "An error occured";

      // die("Error - invalid artist / too many artists found");
    }
    // kill db connection
    if (isset($connection)) {
      mysqli_close($connection);
    }
}

load_new_codes($artistName, $albumName, $promoId, $unusedCodes);

?>
