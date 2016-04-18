<?php
#======================================================================================
	function updateNodes(){
		$query = "SELECT nodeID_8 FROM node";
		$result = mysql_query($query);
		if(!$result){
			echo "Error grabbing the station ID of all the nodes.[Update_Nodes.php]</br>";
			echo mysql_error();
		}
		else{
			$count = mysql_num_rows($result);
			if($count){
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					foreach($row as $key => $value){
						if($key){
							$lat_lon = getLatLon($value);
							# value with boolean value of activity
							$activity = getNodeActivity($value);
							# update this node
							#				lon 		lat 	   true/false  nodeID
							UpdateThisNode($lat_lon[0],$lat_lon[1],$activity,$value);
						}
					}
				}
			}
		}
	}
#======================================================================================
	# function to update this particular node 
	function UpdateThisNode($lon,$lat,$active,$ID){
		$sensors = "SELECT * FROM sensors WHERE nodeid_8 = $ID";
		$possible = mysql_query($sensors);
		$count = mysql_num_rows($possible);
		if($count){
			$query = "UPDATE node SET longitude_5 = $lon , latitude_6 = $lat, activeNode_4 = $active  WHERE nodeID_8 = $ID";
			$result = mysql_query($query);
			if(!$result){
			
				if($count){
					echo "Error Updating ". $ID . " into the database.[Update_Nodes.php]</br>";
					echo mysql_error();
				}
			}
		}
		else{
			echo "This node has no sensor data.[Update_Nodes.php]</br>";
		}
	}
#======================================================================================
	# function to return if the node is active or not IE true or false
	function getNodeActivity($ID){
		# gets todays day 
		$today = date("y-m-d");
		# gets all sensors with this day if any, max is needed to get the most recent of these sensor points
		$query = "SELECT max(DATE_FORMAT(createdAt, '%y-%m-%d')) FROM sensors WHERE nodeid_8 = $ID";
		$result = mysql_query($query);
		if(!$result){
			#echo "Error grabbing all sensor points with todays date.[Update_Nodes.php]</br>";
			echo mysql_error();
		}
		else{
			if($row = mysql_fetch_array($result)){
				if($row[0]==$today){
					return 1;
				}
				else{
					return 0;
				}
			}
		}
	}
#======================================================================================	
	# function to return the lat and longitude in an array. 
	function getLatLon($ID){
		# get this nodes longitude
		$longitude = "SELECT long_5 FROM sensors WHERE nodeid_8 = $ID ORDER BY createdAt DESC LIMIT 1";
		# get this nodes latitude
		$latitude = "SELECT lat_6 FROM sensors WHERE nodeid_8 = $ID ORDER BY createdAt DESC LIMIT 1";
		# get their results
		$latresult = mysql_query($longitude);
		$lonresult = mysql_query($latitude);
		# create an array to return
		$latlon = array();
		if((!$latresult)||(!$lonresult)){
			echo "Error grabbing the latitude or longitude from this station $ID.[Upcate_Nodes.php]</br>";
			echo mysql_error();
		}
		else{
			if($lat = mysql_fetch_array($latresult)){
				#this will return the value of this array of just one. the latitude
				$latlon[0] = $lat[0];
			}
			else{
				$latlon[0] = 0;
			}
			if($lon= mysql_fetch_array($lonresult)){
				#this will return the value of this array of just one. the longitude
				$latlon[1] = $lon[0];
			}
			else{
				$latlon[1] = 0;
			}
			return $latlon;
		}
	}
#======================================================================================	
?>