<html>
	<body>
		<form action="" method="post" style="position: absolute; top: 45%; left: 45%; text-align: center;">
			<?php
                session_start();
				if (isset($_POST['userid'])) {
					$username=$_POST['userid'];
					$password=$_POST['pass'];
                    if ($username == preg_replace("/[^a-zA-Z0-9_.]/", "", $username) && $password == preg_replace("/[^a-zA-Z0-9_.]/", "", $password)) {
                        $db = new Sqlite3("../main.db");
                        $results = $db->query('select * from users where name="'.$username.'";');
                        while ($row = $results->fetchArray()) {
                            if (md5($password) == $row['passhash']) {
                                echo "<p style='color:green'>OK!</p>";
                                $_SESSION["userid"] = $username;
                                header("location: index.php");
                                exit;
                            }
                        }
                    } else {
                        echo "Somethings wrong.";
                    }
				}
			?>
			<input type="username" id="userid" name="userid" placeholder="Username">
			<br/><br/>
			<input type="password" id="pass" name="pass" placeholder="Password">
			<br/><br/>
			<input type="submit" id="submit" name="submit" value="Login">
		</form>
	</body>
</html>
