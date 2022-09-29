<?php
	session_start();
	if (!isset($_SESSION["userid"])) {
		header("location: login.php");
		exit;
	} 
?>
<html>
	<body>
        <form action="upload.php" method="post" style="text-align: center;" enctype="multipart/form-data">
            <?php
            if (isset($_POST['submit']) && $_FILES['fileUpload']['error'] == 0) {
                $descriptionRaw = $_POST['description'];
                $descriptionClean = preg_replace("/[^a-zA-Z0-9_ .]/", "", $descriptionRaw);
                $filepathRaw = $_FILES['fileUpload']['name'];
                $filepathClean = preg_replace("/[^a-zA-Z0-9_.]/", "", $filepathRaw);
                if ($descriptionRaw == $descriptionClean && $filepathRaw == $filepathClean) {
                    $fileName = $_FILES['fileUpload']['tmp_name'];
                    $filename = $_SERVER['DOCUMENT_ROOT']."/../uploads/".$filepathClean;
                    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0 && file_exists($filename) == FALSE && $_FILES["fileUpload"]["size"] < 500000) {
                        move_uploaded_file($fileName, $filename);
                        $userid = "test";
                        $db = new Sqlite3('../main.db');
                        $result=$db->query("insert into files values('".$filepathClean."', 'test','".$descriptionClean."',0,0)");
                        echo "<p style='color: green;'>Upload was successful.</p>";
                    } else {
                        echo "<p style='color: red;'>An error occured. Either the file exists or it is too large. Try again.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Please only use numbers, capital or lowercase alphabet letters for the description and file name.</p>";
                }
            }
            ?>
            <h3>Like to publish your own work?</h3>
            <textarea id="description" name="description" placeholder="Description" style="height: 100px; width: 250px;"></textarea>
            <br/><br/>
            <input type="file" id="fileUpload" name="fileUpload">
            <br/><br/>
            <input type="submit" id="submit" name="submit" value="Upload" />
        </form>
	<h2 style="text-align: left;">Full list</h2>
	<?php
		$arrFiles = array();
		$handle = opendir($_SERVER['DOCUMENT_ROOT']."/../uploads");
 
		if ($handle) {
    			while (($entry = readdir($handle)) !== FALSE) {
        			$arrFiles[] = $entry;
    			}
		}	
		closedir($handle);
		for ($i=2;$i<count($arrFiles);$i++) {
			echo "</br><p>";
			echo htmlentities($arrFiles[$i]);
			echo " <a href='download.php?filename=${arrFiles[$i]}'>Download</a></p>";
			$db = new Sqlite3('../main.db');
			$results = $db->query('select * from files where name="'.$arrFiles[$i].'";');
			while ($row = $results->fetchArray()) {
				echo "<p>Description: ".htmlentities($row['description'])."</p></br>";
				echo "<p>Price: $".htmlentities($row['price'])."</p>";
			}	
		}
	?>
	</body>
</html>
