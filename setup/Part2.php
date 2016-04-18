<html>
<?php
	if(isset($_GET['action'])=='submitUser'){
		submitUser();
	}
	if(isset($_GET['action'])=='deleteUser'){
		deleteUser();
	}
?>
<!-- =========================HTML TO SUBMIT A USER=======================-->
<body>
	<center>
		<h1> Add user </h1>
		<form method="post" action="?action=submitUser">
				<!-- 
					Here we will add users to the website
				 -->
				Username:		<input type="text" name="user1"/><br/>
				Password:		<input type="text" name="pass1"/><br/>
				State:			<input type="text" name="state1"/><br/>
				Email:			<input type="text" name="email1"/><br/>
				GroupID:		<input type="text" name="groupID1"/><br/>	
			<input type="submit" value="Submit" name="User"/>
		</form>
	</center>
<!-- ========================HTML TO DELET A USER=========================-->
	<center>
		<h3>
			Admin rights only. Delete user from website.
		</h3>
	
		<form method="post" action="?action=deletefunc">
			CUST_ID:	<input type="text" name="cust_id"/>
						<input type="submit" value="submit" name="del"/>
		</form>
	</center>
	</body>
<!-- =========================PHP CODE FOR THIS PAGE=======================================-->
<?php
	/*				Show the table for USER_SEE				*/
	$query = "select * from user_see";

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

#	FUCNTION to sumbit a user that is not already created. ie username is not taken.
	function submitUser(){
		if(isset($_POST['User'])){

			#set up the variables to be used.
			$username = $_POST["user1"];
			$password = $_POST["pass1"];
			$state = $_POST["state1"];
			$email = $_POST["email1"];
			$groupID = $_POST["groupID1"];

			#insert into user, and into user_see
			$query = "INSERT INTO emails VALUES('$username',$password,$state,$email,'$groupID')";

			#set up connection
			$host = "cs.okstate.edu";
			$dbusername = "mwhelan";
			$dbpassword = "Iqoo8ia";
			$db_name="mwhelan";
			
			# create connection to database and check connection
			mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
			mysql_select_db("$db_name")or die("cannot select DB");

			$result=mysql_query($query);
			if($result){
				echo "success";
			}
			else{
				echo "false";
			 	echo mysql_error();
			}
		}
	}
	# Get the name of the User from database for comparison
	function name_($name){
		$user = $name;
		#get the name from the database return null if not there
		$query = "SELECT username_1 FROM user WHERE username_1='$user'";
		$result = mysql_query($query);
		
		if(!$result){
			echo mysql_error();
		}
		else
		{
			#create array of all objects with that name. Should only be one.
			while($row = $value = mysql_fetch_object($result)){
				$array[] = $row->cust_id;
			}
			$x = $array[0];
			return $x;
		}
	}
	# Get the email of the User from the database for comparison
	function email_($email){
		$useremail = $email;
		#get the name from the database return null if not there
		$query = "SELECT email FROM user WHERE email='$useremail'";
		$result = mysql_query($query);
		
		if(!$result){
			echo mysql_error();
		}
		else
		{
			#create array of all objects with that email. Should only be one.
			while($row = $value = mysql_fetch_object($result)){
				$array[] = $row->cust_id;
			}
			$x = $array[0];
			return $x;
		}
	}




	function deleteUser(){
		if (isset($_POST['del'])){
			$username = $_POST["username"];
	 		$query = "delete from emails where username='$username'";
			$host = "cs.okstate.edu";
			$dbusername = "mwhelan";
			$dbpassword = "Iqoo8ia";
			$db_name="mwhelan";

			mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
			mysql_select_db("$db_name")or die("cannot select DB");

			$result=mysql_query($query);
			
			if(!$result){
				echo mysql_error();
			}
			else
			{
		 		echo "tuple with username=$username is deleted successfully<br/>";
			}	
		}
	}
?>
</html>