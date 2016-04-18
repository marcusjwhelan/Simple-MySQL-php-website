<?php
include 'Update_Nodes.php';
	updateNodes();
#======================================================================================
	function updateStates(){
		$query = "SELECT state_2 FROM state";
		$result = mysql_query($query);
		if(!$result){
			echo "Error grabbing the names of all the states[Update_States.php]</br>";
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
							# $value is equal to this current states name
							$in_state = StationCount($value);
							#in_state is equal to the nuber of nodes in the state
							#now we can update this states value
							if($in_state[0]>0){
								StateUpdate($in_state[0],$in_state[1],$value);
							}
							else{
								StateUpdate(0,0,$value);
							}
						}
					}
				}
			}
		}
	}
#======================================================================================
	# a function to update a specific states total number of nodes within its 
	# coordinates bounds. 
	function StateUpdate($count,$active,$state){
		$query = "UPDATE state SET nodes_3 = $count , active_4 = $active  WHERE state_2 = '$state'";
		$result = mysql_query($query);
		if(!$result){
			if($state){
				echo "Error Updating ". $state . " into the database.[Update_States.php]</br>";
			}
			echo mysql_error();
		}
	}
#======================================================================================
	# a function to return the number of nodes inside a states particulary bounds
	function StationCount($state){
		$State = $state; # equal to states name

		#have a count ready for every station 
		$nodeCount = 0;
		#a count for the number of active nodes
		$activeCount =0;
		#now lets get every node and check if the node is within the bounds of this state
		$query = "SELECT nodeID_8 FROM node";
		$result = mysql_query($query);

		if(!$result){
			echo "Error grabbing all station ids from node.[Update_States.php]</br>";
			echo mysql_error();
		}
		else{
			$count = mysql_num_rows($result);
			if($count){
				# new way some how works and the old way didnt
				$columns = mysql_num_fields($result);
				for($i=0;$i<$columns;$i++){
					$fieldName = mysql_field_name($result,$i);
					while($row=mysql_fetch_assoc($result,$i)){
						# value is the node id for this particular station
						foreach($row as $column=>$value){
							# This sets value to the current column
						}
						#check how many station are in state send back in array
						$is_or_not = checkIfInState($value,$State);
						# this station is in this state
						if($is_or_not){
							$nodeCount = $nodeCount + 1;
							#check if the statetion is active send back in array
							$is_On = checkIfStationActive($value,$State);
							if($is_On){
								$activeCount = $activeCount + 1;
							}
						}
					}
					$counts = array($nodeCount,$activeCount);
					return $counts;
				}

				/*
				# There are rows. now check if there is a match
				while($row=mysql_fetch_array($result)){
					#cycle through each row of the $result column
					# $value is equal to this nodes ID
					foreach($row as $key => $value){
						if($key){
							#is_or_not is a boolean true or false if in the state
							$is_or_not = checkIfInState($value,$State);
							if($is_or_not){
								$nodeCount = $nodeCount + 1;
								
							}
						}
					}
					# After cycling through each node return the total number of nodes in state
					return $nodeCount;
				}
				*/
			}
		}
	}
#======================================================================================	
	# a funciton to return if the station is active or not. boolean true false
	function checkIfStationActive($nodeID,$State){
		$state = $State;
		$ID = $nodeID;

		$query = "SELECT activeNode_4 FROM node WHERE nodeID_8 = $ID";
		$result = mysql_query($query);
		if(!$result){
			echo "Error grabbing the active paramater of node " . $ID .".[Update_States.php]</br>";
		}
		else{
			while($row = mysql_fetch_array($result)){
				#this will return the value of this array of just one. the longitude
				if($row[0]){
					return true;
				}
			}
		}
	}
#======================================================================================	
	# a function to return true or false if this station is within this states bounds.
	function checkIfInState($node,$state){
		$station = $node;
		$State = $state;
		# a count for chekcing the coordinates.
		$count = 0;

		# alot of ectra functions to retrieve each coordinate of this state. 
		$query = "SELECT * FROM state WHERE state_2 = '$State'";
		$result = mysql_query($query);
		if(!$result){
			echo "Error finishing query to return specific state from database.[Update_States.php]</br>";
		}
		else{
			while ($row = mysql_fetch_array($result)) {
				$nodeLat = getNodeLat($station);
				$nodeLon = getNodeLon($station);
				/*
				$row[1]=NWlat , [2]=NWlon,[3]=SWlat,[4]=SWlon,[5]=NElat,[6]=NElon
							  , [7]=SElat,[8]=SElon

					Alright its late and I came up with this and it works. or it should.
					Since some boundaries of the states are all crooked that means some
					times the exact coordinate of the station could not be within the 
					bounds of all 4 lat,lon coordinate points of a state. 

					But if I create a count and increment this count every time it is
					within less than a NE NW lat, if it is greater than a SW SE lat, 
					less than a NW SW lon, and greater than a NE SE lon

					Then if the count is = to 3 then it is between 3 points of the 
					states boundaries. A triangle made by the state. ALthough this 
					traingle can be made by any 3 points of the 4 points of the state 
					to accomidate the stations point.
				*/
				#    lat   < NWlat 		 	lon   > NWlon
				if(($nodeLat < $row[1])&&($nodeLon > $row[2])){
					$count = $count + 1;
				}
				#    lat   > SWlat 		 	lon   > SWlon
				if(($nodeLat > $row[3])&&($nodeLon > $row[4])){
					$count = $count + 1;
				}
				#    lat   < NElat 		 	lon   < NElon
				if(($nodeLat < $row[5])&&($nodeLon < $row[6])){
					$count = $count + 1;
				}
				#    lat   > SElat 		 	lon   < SElon
				if(($nodeLat > $row[7])&&($nodeLon < $row[8])){
					$count = $count + 1;
				}
				if($count >=3){
					return true;
				}
			}
		}
	}
#======================================================================================	
	# 2 funcitons to return the Specific nodes coordinates
	function getNodeLat($station){
		$node = $station;
		$query = "SELECT latitude_6 FROM node WHERE nodeID_8 = $node";
		$result = mysql_query($query);
		if(!$result){
			echo "Error returning specific nodes latitude from node. [Update_States.php].</br>";
		}
		else{
			while($row = mysql_fetch_array($result)){
				#this will return the value of this array of just one. the latitude
				return $row[0];
			}
		}
	}
	function getNodeLon($station){
		$node = $station;
		$query = "SELECT longitude_5 FROM node WHERE nodeID_8 = $node";
		$result = mysql_query($query);
		if(!$result){
			echo "Error returning specific nodes longitude from node. [Update_States.php].</br>";
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