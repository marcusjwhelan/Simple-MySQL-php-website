<?php

#======================================================================================
		/*				Show the table for User		*/
	$query = "SELECT * FROM user";

	#connect to database
	$host = "cs.okstate.edu";   # localhost:3306 or 127.0.0.1:3306
	$dbusername =  "*******";    # root
	$dbpassword = "********";   # root
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
?>
