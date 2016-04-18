<?php
#======================================================================================
	# admin login to admin page
	if(isset($_POST['adminLogin'])){

		# ADMIN USERNAME AND PASSWORD
		$useradmin = "admin";
		$passadmin = "password";

		# Get the username and password entered by the user if not empty
		if((!empty($_POST["adminpass"]))&&(!empty($_POST["adminuser"]))){
			# since both have values save them
			$username = $_POST["adminuser"];
			$password = $_POST["adminpass"];

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
				match the admin username and password
			*/
			if(($username === "admin") && ($password === "password")){
				# then transfer them to the next page
				header('Location: http://cs.okstate.edu/~mwhelan/Admin_Page.php');
			}
			else{
				echo "Sorry the Username or Password you entered is incorrect.";
			}
		}
		else{
			echo "Sorry but you need to enter both a username and a password.";
		}
	}
?>