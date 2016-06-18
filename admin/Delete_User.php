<?php
#======================================================================================
	# connect to database 
	$host = "cs.okstate.edu";
	$dbusername = "*****";
	$dbpassword = "******";
	$db_name="mwhelan";

	mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");
#======================================================================================

	#admin username and password
	$adminuser = "admin";
	$adminpass = "password";

	session_start();
	$username = $_SESSION['usern'];
	$password = $_SESSION['passw'];
	$dusername = $_SESSION['dusern'];
	# get back a boolean if this username password combo exists
	#$user_pass_match = getMatching($username,$password);
	# return a boolean if this username exists
	$exists = getUser($dusername);
	# either its a user 						or its an admin
	#if(($user_pass_match||(($adminuser == $username)&&($adminpass == $password)))&&($exists)){
	if((($adminuser == $username)&&($adminpass == $password))||$exists){
		# get group id function is in User_Page.php
		deleteSensors($dusername);
		$query = "DELETE FROM user WHERE username_1 = '$dusername'";
		$result = mysql_query($query);
		if(!$result){
			echo mysql_error();
		}
		else{
			# now delete from user_ see table as well. the last step
			user_see_DELETE($dusername);
			#now Show the table
			showNewTable();
		}
	}
	else{
		echo "Sorry but that password username combination does not work.</br>";
		echo "Or the username to be deleted does not exist.";
	}
#======================================================================================
	function deleteSensors($dusername){
		# this should return all node IDs associated with this user.
		$query = "SELECT nodeID_8 FROM node WHERE groupid_7 = (SELECT groupID_7 FROM user WHERE username_1 = '$dusername')";
		$result = mysql_query($query);
		if($result){
			$count = mysql_num_rows($result);
			if($count){
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key){
							deleteThisSensor($value);
							deleteThisNode($value);
						}
					}
				}
			}
		}
		else{
			echo mysql_error();
		}
	}
#======================================================================================
	# function to delete all nodes with this node id
	function deleteThisNode($nodeID){
		$query = "DELETE FROM node WHERE nodeID_8 = $nodeID";
		$result = mysql_query($query);
		if(!$result){
			echo mysql_error();
		}
	}
#======================================================================================
	# funciton to delete all sensors with this node id
	function deleteThisSensor($nodeID){
		$query = "DELETE FROM sensors WHERE nodeid_8 = $nodeID";
		$result = mysql_query($query);
		if(!$result){
			echo mysql_error();
		}
	}
#======================================================================================
	#function to return boolean if username exists
	function getUser($dusername){
		$query = "SELECT * FROM user WHERE username_1 = '$dusername'";
		$result = mysql_query($query);
		if($result){
			$count= mysql_num_rows($result);
			if($count){
				return true;
			}
			else{
				return false;
			}
		}
	}
#======================================================================================
	# function to return boolean if the username and password exist in the database
	/*function getMatching($usern,$pass){
		# get the password from the username
		$query1 = "SELECT password FROM user WHERE username_1 = '$usern'";
		# get the username from the password
		$query2 = "SELECT username_1 FROM user WHERE password = '$pass'";
		$result1 = mysql_query($query1);
		$result2 = mysql_query($query2);
		#  password   username
		if($result1&&$result2){
			#             password array 				username array
			if(($row1 = mysql_fetch_array($result1))&&($row2 = mysql_fetch_array($result2))){
				# 	password 		   username
				if(($row1[0]==$pass)&&($row2[0]==$usern)){
					return true;
				}
				else{
					return false;
				}
			}
		}
	}*/
#======================================================================================
	# here we take in the username that we want to delete and delete it form 
	# the user visible data from user_see table 
	function user_see_DELETE($username){
		$user = $username;

		$query = "DELETE FROM user_see WHERE userName_1 = '$user'";
		$result = mysql_query($query);
		if(!$result){
			echo "from delete into user_see";
			echo mysql_error();
		}
	}
#======================================================================================
/*				Show the table for USER_SEE				*/
	function showNewTable(){
		$query = "SELECT * FROM user_see";

		#connect to database
		$host = "cs.okstate.edu";   # localhost:3306 or 127.0.0.1:3306
		$dbusername =  "mwhelan";    # root
		$dbpassword = "Iqoo8ia";   # root
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
		echo "</table></center>";
	}
#======================================================================================
?>
