<?php
	session_start();
	$key_path = 'task-2-a1-dca48e576415.json';
	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $key_path);
	require __DIR__ . '/vendor/autoload.php';
	use Google\Cloud\Datastore\DatastoreClient;
	use Google\Cloud\Datastore\Entity;
	use Google\Cloud\Datastore\EntityIterator;
	use Google\Cloud\Datastore\Key;
	use Google\Cloud\Datastore\Query\Query;

	$projectId = 't2-s3668759';
	$datastore = new DatastoreClient(['projectId' => $projectId]);

	if (isset($_POST['user_change_but'])) {
		$newname = $_POST['txt_newname'];
		$uname = $_SESSION['id'];
		$kind = 'user';

		if ($newname != "") {
			$transaction = $datastore->transaction();
			$key = $datastore->key($kind, $uname);
			$task = $transaction->lookup($key);
			$task['name'] = $newname;
			$transaction->update($task);
			$transaction->commit();

			$_SESSION['uname'] = $newname;

		    echo '<script type="text/javascript">
		    window.location = "/main";
		    </script>';
		}
		else {
			echo 'Username cannot be empty<br>';
		}
	}
?>

<!doctype html>
<html>
	<head>
    	<title>Name Change</title>
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
		            <h1>Change Username</h1>
		            <h5>Please enter your updated name</h5>
		            <div>
		                <input type="text" class="textbox" id="txt_newname" name="txt_newname" placeholder="Current Name" />
		            </div>
		            <div>
		                <input type="submit" value="Submit" name="user_change_but" id="user_change_but" />
		            </div>
		        </div>
		    </form>
		</div>
	</body>
</html>