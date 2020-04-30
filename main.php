<?php
	session_start();

	if (isset($_POST['change_name'])) {
		echo '<script type="text/javascript">
		window.location = "/name";
		</script>';
	}
	if (isset($_POST['change_pass'])) {
		echo '<script type="text/javascript">
		window.location = "/password";
		</script>';
	}
?>

<!doctype html>
<html>
    <head>
    	<title>Main</title>
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
			    width: 200px;
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
    	<body>
			<div class="container">
			    <form method="post" action="">
			        <div id="div_login">
				        <h1>Homepage</h1>
				        <h2>Welcome: 
				        <?php
				        	echo $_SESSION['uname'];
				        ?>
				        </h2>
				        <div>
					        <input type="submit" value="Change Name" name="change_name" id="change_name">
					        <input type="submit" value="Change Password" name="change_pass" id="change_pass">
					    </div>
					</div>
				</form>
			</div>
    </body>
</html>