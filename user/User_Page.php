<html>
<body>
	<center>
<?php
#======================================================================================
#				Lets connect now so we dont have to over and over 
#======================================================================================
	#connect to database
	$host = "cs.okstate.edu";   # localhost:3306 or 127.0.0.1:3306
	$dbusername =  "******";    # root
	$dbpassword = "******";   # root
	$db_name= "mwhelan";
	mysql_connect("$host", "$dbusername", "$dbpassword")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

#======================================================================================
		/*				Show the table for USER			*/
	#if (isset($_POST['login'])){
		#$username = $_POST["user3"];
		#here we capture that sessions value
		session_start();
		$username = $_SESSION['username'];

		$query = "SELECT * FROM user WHERE username_1 = '$username'";

		#show table of users.
		$result=mysql_query($query);
		$count=mysql_num_rows($result);

		#cycle through each row and print them out in a table on the browser
			if(($count > 0)){
				echo "<center>";
				echo "<table border='2'>
					<tr><th>";
				# Name to be printed as title of columns
				echo "Username</th>
						<th>Password</th>
						<th>State</th>
						<th>Email</th>
						<th>Group ID</th>
					</tr>";
				# Actual contents of table from database
				while($row = mysql_fetch_array($result)){
					echo "<tr>
						<td>".$row["username_1"]."</td>
						<td>".$row["password"]."</td>
						<td>".$row["region_2"]."</td>
						<td>".$row["email"]."</td>
						<td>".$row["groupID_7"]."</td>
					</tr>";
					$count=$count-1;
				}
			}
		echo "</table></center>";
	#}
#======================================================================================
?>
	<!-- 		A table of all the stations the user owns      -->
	<h1>	Welcome		</h1>
		<p> Check out your stations <p>
<?php
include 'Update_Nodes.php';
		
	#if (isset($_POST['login'])){
		#$userID = getUserID($_POST["user3"]);
		$userID = getUserID($_SESSION['username']);

		#now update the nodes before you show them
		updateNodes();

		$query = "SELECT * FROM node WHERE groupid_7 = $userID";
		$result = mysql_query($query);
		if(!$result){
			echo "Error retrieving the stations owned by this user.[User_Page.php]</br>";
			echo mysql_error();
		}
		else{
			$count = mysql_num_rows($result);
			if(($count > 0)){
				echo "<center>";
				echo "<table border='2'>
					<tr><th>";
				# Name to be printed as title of columns
				echo "Longitude</th>
						<th>Latitude</th>
						<th>Group ID</th>
						<th>Station ID</th>
						<th>Active</th>
					</tr>";
				# Actual contents of table from database
				while($row = mysql_fetch_array($result)){
					echo "<tr>
						<td>".$row["longitude_5"]."</td>
						<td>".$row["latitude_6"]."</td>
						<td>".$row["groupid_7"]."</td>
						<td>".$row["nodeID_8"]."</td>
						<td>".$row["activeNode_4"]."</td>
					</tr>";
					$count=$count-1;
				}
			}
		echo "</table></center>";
		}
	#}
#======================================================================================
	# a function to return Station id of the user to get all his stations.
	function getUserID($user){
		$query = "SELECT groupID_7 FROM user WHERE username_1 = '$user'";
		$result = mysql_query($query);
		if(!$result){
			echo "Error grabbing the groupID from the user table.[User_Page.php]</br>";
			echo mysql_error();
		}
		else{
			while($row = mysql_fetch_array($result)){
				#this will return the value of this array of just one. the longitude
				return $row[0];
			}
		}
	}
#======================================================================================
?>
<?php
	if(isset($_GET['action'])=='addStation'){
		addStation();
	}
?>

	<h3> Add a station </h3>
		<form method="post" action="?action=addStation">
				<!-- 
					Here we will add stations to the website
				 -->
				Station ID:		<input type="text" name="node_id"/><br/>	
			<input type="submit" value="Add Station" name="station"/>
		</form>
<?php
#======================================================================================
	# a function to add this station to the website
	function addStation(){
		if (isset($_POST['station'])){

			# Get the username and password entered by the user if not empty
			if(!empty($_POST["node_id"])){
				# since both have values save them
				$nodeID = $_POST["node_id"];

				/* 
					Here we will check if this node ID has been taken
				*/
				$node = _node($nodeID);
				if($node === false){
					# get the userID to insert into the node table row
					$userID = getUserID($_SESSION['username']);
					# if all is well insert this new node into the table
					insertIntoNode($userID,$nodeID);
					# then update the table
					updateNodes();
					header("Refresh:0");
				}
				else{
					echo "Sorry this station ID has already been taken.[User_Page.php]</br>";
				}
			}
			else{
				echo "Sorry but you need to enter a Station ID.";
			}
		}
	}
#======================================================================================
	function insertIntoNode($groupID,$nodeID){
		#								(lon,lat,groupid,nodeid, active)
		$query = "INSERT INTO node VALUES (0,0,$groupID,$nodeID,0)";
		$result = mysql_query($query);
		if(!$result){
			echo "There was an error inserting this station into the database.[User_Page.php]</br>";
		}

	}
#======================================================================================
	# a function to return if this is actually the users groupID
	function _node($nodeid){
		$query = "SELECT * FROM node WHERE nodeID_8 = $nodeid";
		$result = mysql_query($query);
		if(!$result){
			echo "Error retrieving any stations with ". $nodeid. " as the station ID.[User_Page.php]</br>";
		}
		else{
			$count = mysql_num_rows($result);
			if(!$count){
				# there are no rows hurray you can move on
				return false;
			}
			else{
				# sorry buddy that node id has been taken.
				return true;
			}
		}
	}
#======================================================================================
?>
<?php
	if(isset($_GET['action'])=='addStationData'){
		addStationData();
	}
?>
 	<h3> Enter data into a station </h3>
 		<p> Please only enter longitude and latitude points withing the lower 48 states.</p>
 		<form method="post" action="?action=addStationData">
				<!-- 
					Here we will add data to a particular station
				 -->
				Humidity:		<input type="text" name="hum"/>EX: 20<br/>	
				Temperature:	<input type="text" name="temp"/>EX: 80<br/>
				Dew Point:		<input type="text" name="d"/>EX: 50<br/>
				Wind Speed:		<input type="text" name="sp"/>EX: 7<br/>
				Wind Direction:	<input type="text" name="dir"/>EX: 360<br/>
				Pressure:		<input type="text" name="pres"/>EX: 32<br/>
				Latitude:		<input type="text" name="lat"/>EX: 40<br/>
				Longitude:		<input type="text" name="lon"/>EX: -100<br/>
				Station ID:		<input type="text" name="id"/>EX: 5<br/>
			<input type="submit" value="Add Station Data" name="data"/>
		</form>
<?php

#======================================================================================
	function addStationData(){
		if (isset($_POST['data'])){
			# make sure all fields are filled
			if((!empty($_POST["hum"]))&&(!empty($_POST["temp"]))&&(!empty($_POST["d"]))&&(!empty($_POST["sp"]))&&(!empty($_POST["dir"]))&&(!empty($_POST["pres"]))&&(!empty($_POST["lat"]))&&(!empty($_POST["lat"]))&&(!empty($_POST["lon"]))&&(!empty($_POST["id"]))){
				$humidity = $_POST["hum"];
				$temp = $_POST["temp"];
				$dew = $_POST["d"];
				$speed = $_POST["sp"];
				$direction = $_POST["dir"];
				$pressure = $_POST["pres"];
				$lat = $_POST["lat"];
				$lon = $_POST["lon"];
				$nodeID = $_POST["id"];
				$node = _node($nodeID);
				if($node === true){
					# get the GroupID to check if the node id entered is theirs
					$GroupID = getUserID($_SESSION['username']);
					# check if the supposed station ID belongs to the user
					$match = getAmatch($nodeID);
					if($match == $GroupID){
						# insert data now 
						InsertIntoSensors($humidity,$temp,$dew,$speed,$direction,$pressure,$lat,$lon,$nodeID);
						# update the nodes
						updateNodes();
					}
					else{
						echo "Sorry but that station does not belong to you.[User_Page.php]</br>";
					}
				}
				else{
					echo "Sorry this station ID does not exist.[User_Page.php]</br>";
				}
			}
			else{
				echo "Sorry but you need to enter data into all fields.";
			}
		}
	}
#======================================================================================
	# a function to insert data into sensors table
	function InsertIntoSensors($humidity,$temp,$dew,$speed,$direction,$pressure,$lat,$lon,$nodeID){
		$query = "INSERT INTO sensors VALUES($humidity,$temp,$dew,$speed,$direction,$pressure,$lat,$lon,NOW(),$nodeID)";
		$result = mysql_query($query);
		if(!$result){
			echo "Error inserting data into Sensors table.[User_Page.php]</br>";
		}
	}
#======================================================================================
	# a function to test if the proposed node id is a part of the current users group
	function getAmatch($nodeID){
		$query = "SELECT groupid_7 FROM node WHERE nodeID_8 = $nodeID";
		$result = mysql_query($query);
		if(!$result){
			echo "Error selecting the GroupID with associated station ID ". $nodeID . ".</br>";
			echo mysql_error();
		}
		else{
			if($row = mysql_num_rows($result)){
				# this will return the group ID of this particular Node. ALl nodes have unique ids.
				return $row[0];
			}
		}
	}
#======================================================================================
?>
<?php
	if(isset($_GET['action'])=='mostRecent'){
		getMostRecent();
	}
?>
	<h3> Get most recent data point from what Station?</h3>
	<form method="post" action="?action=mostRecent">
			Station ID:		<input type="text" name="stationid"/><br/>	
		<input type="submit" value="Data" name="recent"/>
	</form>
<?php
#======================================================================================
	function getMostRecent(){
		if (isset($_POST['recent'])){
			$nodeID = $_POST['stationid'];
			$query = "SELECT * FROM sensors WHERE nodeid_8 = $nodeID GROUP BY createdAt DESC LIMIT 1";
			$result = mysql_query($query);
			if($result){
				$count = mysql_num_rows($result);
				if(($count > 0)){
					echo "<center>";
					echo "<table border='2'>
						<tr><th>";
					# Name to be printed as title of columns
					echo "Humidity</th>
						<th>Temperature</th>
						<th>Dew Point</th>
						<th>Wind Speed</th>
						<th>Wind Direction</th>
						<th>Pressure</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Created At</th>
						<th>Station ID</th>
						</tr>";
					# Actual contents of table from database
					while($row = mysql_fetch_array($result)){
						echo "<tr>
							<td>".$row['humidity']."</td>
							<td>".$row['temp']."</td>
							<td>".$row['dew']."</td>
							<td>".$row['speed']."</td>
							<td>".$row['direction']."</td>
							<td>".$row['pressure']."</td>
							<td>".$row['lat_6']."</td>
							<td>".$row['long_5']."</td>
							<td>".$row['createdAt']."</td>
							<td>".$row['nodeid_8']."</td>
						</tr>";
						$count=$count-1;
					}
				}
				echo "</table></center>";
			}
		}
	}
#======================================================================================	
?>
<?php
	if(isset($_GET['action'])=='getSpecificSensor'){
		getSensorData();
	}
?>
	<h3> Get all data points of specific station</h3>
	<form method="post" action="?action=getSpecificSensor">
			Station ID:		<input type="text" name="station_ID"/><br/>	
		<input type="submit" value="Humidity" name="h"/>
		<input type="submit" value="Temperature" name="t"/>
		<input type="submit" value="Dew Point" name="dp"/>
		<input type="submit" value="Wind Speed" namdp="ws"/>
		<input type="submit" value="Wind Direction" dpame="wd"/>
		<input type="submit" value="Pressure" name="p"/>
	</form>
<?php
#======================================================================================
	function getSensorData(){
		if (isset($_POST['h'])){
			TableMyData($_POST['station_ID'],'humidity','Humidity');
		}
		if (isset($_POST['t'])){
			TableMyData($_POST['station_ID'],'temp','Temperature');
		}
		if (isset($_POST['dp'])){
			TableMyData($_POST['station_ID'],'dew','Dew Point');
		}
		if (isset($_POST['ws'])){
			TableMyData($_POST['station_ID'],'speed','Wind Speed');
		}
		if (isset($_POST['wd'])){
			TableMyData($_POST['station_ID'],'direction','Wind Direction');
		}
		if (isset($_POST['p'])){
			TableMyData($_POST['station_ID'],'pressure','Pressure');
		}
	}
#======================================================================================
	#funciton to get specific nodes sensor data
	function TableMyData($nodeID,$column,$name){
		$query = "SELECT $column FROM sensors WHERE nodeid_8 = $nodeID";
		$result = mysql_query($query);
		if($result){
			$count = mysql_num_rows($result);
			if(($count > 0)){
				echo "<center>";
				echo "<table border='2'>
					<tr><th>";
				# Name to be printed as title of columns
				echo "$name</th>
					</tr>";
				# Actual contents of table from database
				while($row = mysql_fetch_array($result)){
					echo "<tr>
						<td>".$row[$column]."</td>
					</tr>";
					$count=$count-1;
				}
			}
			echo "</table></center>";
		}
	}
#======================================================================================
?>
<?php
	if(isset($_GET['action'])=='delThisUser'){
		delThisUser();
	}
?>
<h1> Delete Account </h1>
	<p> This will delete all Stations and station sensor data in your name.</p>
	<form method="post" action="?action=delThisUser"> 
		<input type="submit" value="Delete Account" name="delete"/>
	</form>
<?php
#======================================================================================
	function delThisUser(){
		if (isset($_POST['delete'])){
			/*if((!empty($_POST["user4"]))&&(!empty($_POST["pass4"]))&&
				(!empty($_POST["duser"]))){
				#session_start();
				
			}
			else{
				echo "Sorry but you need to enter something into all fields.</br>";
			}*/
			$_SESSION['dusern']=$_SESSION['username'];
				header('Location: http://cs.okstate.edu/~mwhelan/Delete_User.php');
		}
	}
#======================================================================================
?>
</body>
</html>
