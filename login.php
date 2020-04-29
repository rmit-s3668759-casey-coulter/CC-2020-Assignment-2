<?php
	session_start();
	$key_path = 'CC-Assignment-2-3b7022ccad1e.json';
	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $key_path);
	require __DIR__ . '/vendor/autoload.php';
	use Google\Cloud\Datastore\DatastoreClient;
	use Google\Cloud\Datastore\Entity;
	use Google\Cloud\Datastore\EntityIterator;
	use Google\Cloud\Datastore\Key;
	use Google\Cloud\Datastore\Query\Query;

	$projectId = 't2-s3668759';
	$datastore = new DatastoreClient(['projectId' => $projectId]);

	if (isset($_POST['but_submit'])) {		// checks if the button has posted
		$uname = $_POST['txt_uname'];
		$password = $_POST['txt_pwd'];
		$kind = 'user';

		if ($uname != "" && $password != "") {
			$key = $datastore->key($kind, $uname);
			$result = $datastore->lookup($key);
			
			if (!is_null($result)) {
				echo 'username found<br>';
				if (strcmp($result['password'], $password) == 0) {
					echo 'password correct<br>';
					$_SESSION['id'] = $uname;
					$_SESSION['uname'] = $result['name'];

		        	echo '<script type="text/javascript">
		        	window.location = "/main";
		        	</script>';
				}
				else {
					echo 'User id or password is invalid<br>';
				}
			}
			else {
				echo 'User id or password is invalid<br>';
			}
		}
		else {
			echo 'Please enter into both username and password fields<br>';
		}
	}
?>

<!doctype html>
<html>
	<head>
    	<title>Log In</title>
    	<style>
			.container {
			    width:40%;
			    margin:0 auto;
			}
			#div_login {
			    border: 1px solid gray;
			    border-radius: 3px;
			    width: 470px;
			    height: 270px;
			    box-shadow: 0px 2px 2px 0px  gray;
			    margin: 0 auto;
			}
			#div_login h1 {
			    margin-top: 0px;
			    font-weight: normal;
			    padding: 10px;
			    background-color: cornflowerblue;
			    color: white;
			    font-family: sans-serif;
			}
			#div_login div {
			    clear: both;
			    margin-top: 10px;
			    padding: 5px;
			}
			#div_login .textbox {
			    width: 96%;
			    padding: 7px;
			}
			#div_login input[type=submit] {
			    padding: 7px;
			    width: 100px;
			    background-color: lightseagreen;
			    border: 0px;
			    color: white;
			}
			@media screen and (max-width:720px) {
			    .container {
			        width: 100%;
			    }
			    #div_login {
			        width: 99%;
			    }
			}
    	</style> 
	</head>
	<body>
		<div class="container">
		    <form method="post" action="">
		        <div id="div_login">
		            <h1>Login</h1>
		            <div>
		                <input type="text" class="textbox" id="txt_uname" name="txt_uname" placeholder="Username" />
		            </div>
		            <div>
		                <input type="password" class="textbox" id="txt_uname" name="txt_pwd" placeholder="Password"/>
		            </div>
		            <div>
		                <input type="submit" value="Submit" name="but_submit" id="but_submit" />
		            </div>
		        </div>
		    </form>
		</div>
	</body>
</html>