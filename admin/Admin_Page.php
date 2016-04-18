<html>
<body>
	<center>
<?php

#======================================================================================
#				Lets connect now so we dont have to over and over 
#======================================================================================

	#connect to database
	$host = "cs.okstate.edu";   # localhost:3306 or 127.0.0.1:3306
	$dbusername =  "mwhelan";    # root
	$dbpassword = "Iqoo8ia";   # root
	$db_name= "mwhelan";
	mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

#======================================================================================
		/*				Show the table for USER			*/
	#lets update the website table before we show it. its easy dont worry
	$isThere = updateWebsiteTable();

	$query = "SELECT * FROM website";

	#show table of users.
	$result=mysql_query($query);
	$count=mysql_num_rows($result);

	#cycle through each row and print them out in a table on the browser
		if(($count > 0)|| $isThere){
			echo "<center>";
			echo "<table border='2'>
				<tr><th>";
			# Name to be printed as title of columns
			echo "Users</th>
					<th>Stations</th>
				</tr>";
			# Actual contents of table from database
			while($row = mysql_fetch_array($result)){
				echo "<tr>
					<td>".$row["users_1"]."</td>
					<td>".$row["stations_3"]."</td>
				</tr>";
				$count=$count-1;
			}
		}
	echo "</table></center>";
#======================================================================================
	#update the website table
	function updateWebsiteTable(){
		$userNumber = getUsersCount();
		$nodeNumber = getNodeCount();

		updateUserColumn($userNumber);
		updateNodeColumn($nodeNumber);
		if($userNumber || $nodNumber){
			return true;
		}
		else{
			return false;
		}
	}
	#return the number of users
	function getUsersCount(){
		$query = "SELECT * FROM user";
		$result=mysql_query($query);
		$count=mysql_num_rows($result);
		return $count;
	}
	#return the number of nodes / weather stations
	function getNodeCount(){
		$query = "SELECT * FROM node";
		$result=mysql_query($query);
		$count=mysql_num_rows($result);
		return $count;
	}
	function updateUserColumn($count){
		$query = "UPDATE website SET users_1= $count";
		$result=mysql_query($query);
		if(!$result){
			echo "Sorry an error accured during website table update.1</br>";
			echo mysql_error();
		}
	}
	function updateNodeColumn($count){
		$query = "UPDATE website SET stations_3= $count";
		$result=mysql_query($query);
		if(!$result){
			echo "Sorry an error accured during website table update.2</br>";
			echo mysql_error();
		}
	}
#======================================================================================
?>
<!-- =============  Admin add user =========== -->
	<center>
		<h1> Add user </h1>
		<form method="post" action="Submit_User.php">
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

<!-- =============  see all user data =========== -->
		<form method="post" action="Show_User_Data.php"><br/>
			<input type="submit" value="Show All User Info" name="show"/>
		</form>

<?php
	if(isset($_GET['action'])=='delUser'){
		DeleteUser();
	}
?>
<!-- ============= Admin delete user =========== -->
		<h3>
			Admin rights only. Delete user from website.
		</h3>
		<p> Login</p>
		<form method="post" action="?action=delUser"><br/>
			Username:	<input type="text" name="user2"/><br/>
			Password:  	<input type="text" name="pass2"/><br/>
			<div> Username to delete.</div><br/>
			Username: 	<input type="text" name="duser"/><br/>
						<input type="submit" value="Submit" name="del"/>
		</form>
<?php
#======================================================================================
	function DeleteUser(){
		if (isset($_POST['del'])){
			if((!empty($_POST["user2"]))&&(!empty($_POST["pass2"]))&&
				(!empty($_POST["duser"]))){
				session_start();
				$user2 = $_POST['user2'];
				$pass2 = $_POST['pass2'];
				$duser = $_POST['duser'];
				$_SESSION['usern']=$user2;
				$_SESSION['passw']=$pass2;
				$_SESSION['dusern']=$duser;
				header('Location: http://cs.okstate.edu/~mwhelan/Delete_User.php');
			}
			else{
				echo "Sorry but you need to enter something into all fields.</br>";
			}
		}
	}
#======================================================================================
?>
</body>
<center>
	<h2> State Coordinates plus total stations and active stations.</h2>
<?php

# this is in a different file in admin folder. 
include 'Update_States.php';
#======================================================================================
		/*				Show the table for USER			*/
	# Here we will retrieve the all stations for each state depending on their 
	# GPS coordinates. Also check which stations are active. 
	updateStates();

	$query = "SELECT * FROM state";

	#show table of users.
	$result=mysql_query($query);
	$count=mysql_num_rows($result);

	#cycle through each row and print them out in a table on the browser
		if(($count > 0)|| $isThere){
			echo "<center>";
			echo "<table border='2'>
				<tr><th>";
			# Name to be printed as title of columns
			echo "State</th>
					<th>NW Latitude</th>
					<th>NW Longitude</th>
					<th>SW Latitude</th>
					<th>SW Longitude</th>
					<th>NE Latitude</th>
					<th>NE Longitude</th>
					<th>SE Latitude</th>
					<th>SE Longitude</th>
					<th>Total Stations</th>
					<th>Active Stations</th>
				</tr>";
			# Actual contents of table from database
			while($row = mysql_fetch_array($result)){
				echo "<tr>
					<td>".$row["state_2"]."</td>
					<td>".$row["NWlat"]."</td>
					<td>".$row["NWlon"]."</td>
					<td>".$row["SWlat"]."</td>
					<td>".$row["SWlon"]."</td>
					<td>".$row["NElat"]."</td>
					<td>".$row["NElon"]."</td>
					<td>".$row["SElat"]."</td>
					<td>".$row["SElon"]."</td>
					<td>".$row["nodes_3"]."</td>
					<td>".$row["active_4"]."</td>
				</tr>";
				$count=$count-1;
			}
		}
	echo "</table></center>";
?>
</center>
</html>