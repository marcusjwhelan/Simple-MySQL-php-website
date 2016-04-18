<html>

<?php
	if(isset($_GET['action'])=='Login'){
		Login();
	}
?>

<body>
	<center>
		<h1> User Login </h1>
		<!-- <form method="post" action="User_Login.php"> -->
			<form method="post" action="?action=Login"> 
				<!-- 
					Her user will enter username and password and be sent to the main website page.
				 -->
				Username:		<input type="text" name="user3"/><br/>
				Password:		<input type="text" name="pass3"/><br/>
			<input type="submit" value="Login" name="login"/>
		</form>
	</center>

</html>
<?php
#======================================================================================
	# here we will check to see if the user has admin status by knowing the 
	# username and password of the admin given here.
	function Login(){
		if (isset($_POST['login'])){

			# ADMIN USERNAME AND PASSWORD
			$useradmin = "admin";
			$passadmin = "password";

			# Get the username and password entered by the user if not empty
			if((!empty($_POST["pass3"]))&&(!empty($_POST["user3"]))){
				# since both have values save them
				$username = $_POST["user3"];
				$password = $_POST["pass3"];

				#set up connection
				$host = "cs.okstate.edu";
				$dbusername = "mwhelan";
				$dbpassword = "Iqoo8ia";
				$db_name="mwhelan";
				
				# create connection to database and check connection
				mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
				mysql_select_db("$db_name")or die("cannot select DB");

				/* 
					Here we will check to see if the entered username and password
					were found in the database if not they will get a false and if 
					so they will have true as their value. Also check if they typed 
					in the admin username and password if they want admin access.
				*/
				$name = _user($username);
				$pass = _pass($password);
				if((true=== $name) && ($pass=== true) || ($username === "admin") && ($password === "password")){
					#had to start a session to send this variable to the next page
					session_start();
					$_SESSION['username']=$username;
					# then transfer them to the next page
					header('Location: http://cs.okstate.edu/~mwhelan/User_Page.php');
				}
				else{
					echo "Sorry the Username or Password you entered is incorrect.";
				}
			}
			else{
				echo "Sorry but you need to enter both a username and a password.";
			}
			
		}
	}
	function _user($username){
		$user = $username;

		$query = "SELECT username_1 FROM user WHERE username_1='$user'";
		$result = mysql_query($query);

		if(!$result){
			echo "error at name_, could not grab this name from database</br>";
			echo mysql_error();
		}
		else{
			$count = mysql_num_rows(mysql_query('SELECT * FROM user'));
			if($count){
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key == $user){
							#there was a match so cancel making this . 
							return true;
						}
						else{
							return false;
						}
					}
				}
			}
		}
	}
	function _pass($password){
		$pass = $password;

		$query = "SELECT password FROM user WHERE password='$pass'";
		$result = mysql_query($query);

		if(!$result){
			echo "error at name_, could not grab this password from database</br>";
			echo mysql_error();
		}
		else{
			$count = mysql_num_rows(mysql_query('SELECT * FROM user'));
			if($count){
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key == $user){
							#there was a match so cancel making this . 
							return true;
						}
						else{
							return false;
						}
					}
				}
			}
		}
	}
?>