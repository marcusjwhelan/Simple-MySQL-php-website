<?php
#======================================================================================
#	FUCNTION to sumbit a user that is not already created. ie username is not taken.
	#function submitUser(){
		if(isset($_POST['User'])){

			if((!empty($_POST["user1"]))&&(!empty($_POST["pass1"]))&&
				(!empty($_POST["state1"]))&&(!empty($_POST["email1"]))&&(!empty($_POST["groupID1"]))){
				#set up the variables to be used.
				$username = $_POST["user1"];
				$password = $_POST["pass1"];
				$state = $_POST["state1"];
				$email = $_POST["email1"];
				$groupID = $_POST["groupID1"];

				#insert into user, and into user_see
				$query = "INSERT INTO user VALUES('$username','$password','$state','$email',$groupID)";

				#set up connection
				$host = "cs.okstate.edu";
				$dbusername = "*****";
				$dbpassword = "******";
				$db_name="mwhelan";
				
				# create connection to database and check connection
				mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
				mysql_select_db("$db_name")or die("cannot select DB");

				/* 
					Here we will check to see if the submitted username 
					has already been entered and if so then dont add it
					and send an error message on the browser so the user
					can see that this is not available. 
				*/
				$name = name_($username);
				$group = group_($groupID);
				$mail = email_($email);
				if(($username === $name) || ($email === $mail) || ($group == $groupID)){
					echo "Sorry either that Username, email, or Group ID has been taken<br/>";
				}
				else{
					$result=mysql_query($query);
					if($result){
						# also insert into user_see table for user visible data
						echo "$username has been added to the database.";
						user_see_Insert($username,$state,$groupID);
						#lets set a session with the username to pass to the user page
						session_start();
						$_SESSION['username']=$username;
						header('Location: http://cs.okstate.edu/~mwhelan/User_Page.php');
					}
					else{
						echo "Error from insert into user</br>";
					 	echo mysql_error();
					}
				}
			}
			else{
				echo "Sorry you need to enter data into the text bars to add a User.";
			}
		}
	#}
#======================================================================================
	# here we will insert user visible data to the user_see table
	function user_see_Insert($username,$state,$groupID){
		$username1 = $username;
		$state1 = $state;
		$groupid1 = $groupID;

		$query = "INSERT INTO user_see VALUES('$username1','$state1','$groupid1')";
		$result = mysql_query($query);
		if(!$result){
			echo "Error from insert into user_see";
			echo mysql_error();
		}
	}
#======================================================================================
	# Get the name of the User from database for comparison
	function name_($name){
		$user = $name;
		#get the name from the database return null if not there
		$query = "SELECT username_1 FROM user WHERE username_1='$user'";
		$result = mysql_query($query);

		if(!$result){
			echo "error at name_</br>";
			echo mysql_error();
		}
		else
		{
			# method to check if there are any rows
			$count = mysql_num_rows(mysql_query('SELECT * FROM user'));
			if($count){
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key == $user){
							#there was a match so cancel making this . 
							return $user;
						}
					}
				}
			}
		}
	}
#======================================================================================
	# Get the email of the User from the database for comparison
	function email_($email){
		$useremail = $email;
		#get the name from the database return null if not there
		$query = "SELECT email FROM user WHERE email='$useremail'";
		$result = mysql_query($query);
		
		#to check if the table is empty
		$row = mysql_fetch_array($result);

		if(!$result){
			echo " no result from email_";
			echo mysql_error();
		}
		else
		{
			# method to check if there are any rows
			$count = mysql_num_rows(mysql_query('SELECT * FROM user'));
			if($count){
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key == $useremail){
							#there was a match so cancel making this . 
							return $useremail;
						}
					}
				}
			}
		}
	}
#======================================================================================
	# check to see if this group ID is taken as well
	function group_($ID){
		$groupID = $ID;
		#get the name from the database return null if not there
		$query = "SELECT groupID_7 FROM user WHERE groupID_7=$groupID";
		$result = mysql_query($query);
		
		#to check if the table is empty
		$row = mysql_fetch_array($result);

		if(!$result){
			echo " no result from group_, did you not enter a number?";
			echo mysql_error();
		}
		else
		{
			# method to check if there are any rows
			$count = mysql_num_rows(mysql_query('SELECT * FROM user'));
			if($count){
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key == $groupID){
							#there was a match so cancel making this . 
							#return $groupID;
						}
						else{
							#I HAVE NO IDEA WHY THIS IS DIFFERENT
							return $groupID;
						}
					}
				}
			}
		}
	}
#======================================================================================
	/*				Show the table for USER_SEE				*/
	/*$query = "SELECT * FROM user_see";

	#connect to database
	$host = "cs.okstate.edu";   # localhost:3306 or 127.0.0.1:3306
	$dbusername =  "*****";    # root
	$dbpassword = "*******";   # root
	$db_name= "mwhelan";
	mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	#show table of users.
	$result=mysql_query($query);
	$count=mysql_num_rows($result);

	#cycle through each row and print them out in a table on the browser
		if($count > 0){
			echo "<center>";
			echo "<table border='2'>
				<tr><th>";
			# Name to be printed as title of columns
			echo "Username</th>
					<th>State</th>
					<th>Group ID</th>
				</tr>";
			# Actual contents of table from database
			while($row = mysql_fetch_array($result)){
				echo "<tr>
					<td>".$row["userName_1"]."</td>
					<td>".$row["regioN_2"]."</td>
					<td>".$row["groupId_7"]."</td>
				</tr>";
				$count=$count-1;
			}
		}
	echo "</table></center>";*/
?>
