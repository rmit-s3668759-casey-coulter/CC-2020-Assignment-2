<?php
	session_start();
	$key_path = 'CC-Assignment-2-3b7022ccad1e.json';
	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $key_path);
	require_once __DIR__ . '/vendor/autoload.php';
	use Google\Cloud\Datastore\DatastoreClient;
	use Google\Cloud\Datastore\Entity;
	use Google\Cloud\Datastore\EntityIterator;
	use Google\Cloud\Datastore\Key;
	use Google\Cloud\Datastore\Query\Query;

	$projectId = 'cc-assignment-2-275700';
	$datastore = new DatastoreClient(['projectId' => $projectId]);

	if (isset($_POST['pass_change_but'])) {
		$oldpass = $_POST['txt_curr_pass'];
		$newpass = $_POST['txt_new_pass'];
		$uname = $_SESSION['id'];
		$kind = 'user';

		$key = $datastore->key($kind, $uname);
		$task = $datastore->lookup($key);

		if ($oldpass == $task['password']) {
			$transaction = $datastore->transaction();
			$result = $transaction->lookup($key);
			$result['password'] = $newpass;
			$transaction->update($result);
			$transaction->commit();

		    echo '<script type="text/javascript">
		    window.location = "/";
		    </script>';
		}
		else {
			echo 'User password is incorrect”<br>';
		}
	}
?>

<!doctype html>
<html>
	<head>
    	<title>Password Change</title>
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
		            <h1>Change Password</h1>
		            <h5>Please enter your old and new password</h5>
		            <div>
		                <input type="text" class="textbox" id="txt_curr_pass" name="txt_curr_pass" placeholder="Current Password" />
		            </div>
		            <div>
		                <input type="text" class="textbox" id="txt_new_pass" name="txt_new_pass" placeholder="
		                New Password" />
		            </div>
		            <div>
		                <input type="submit" value="Submit" name="pass_change_but" id="pass_change_but" />
		            </div>
		        </div>
		    </form>
		</div>
	</body>
</html>