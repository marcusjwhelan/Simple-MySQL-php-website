<html>
<?php
	if(isset($_GET['action'])=='New'){
		goToSubmitPage();
	}
	if(isset($_GET['action'])=='loginPage'){
		goToLoginPage();
	}
	if(isset($_GET['action'])=='adminPage'){
		goToadminPage();
	}
?>
<body>
	<center>
		<h1> Home Page </h1>

		<!-- Go Submit a new user-->
		<form method="post" action="?action=New">
			<p> 
				For anyone who wants to join this database and view tables
				please subscribe here on the add user page.
			</p>
			<input type="submit" value="New User" name="new"/>
		</form>

		<!-- Already a user go login -->
		<form method="post" action="loginPage">
			<p> 
				For any already users please login here to see tables
			</p>
			<input type="submit" value="Login" name="old"/>
		</form>

		<!-- Go to the admin page -->
		<form method="post" action="adminPage">
			<p> 
				Are you an admin. Would you like to delete users and enter in data
				for the website then lets go here buddy :).
			</p>
			<input type="submit" value="Admin Login" name="admin"/>
		</form>

	</center>
<?php
#===================================================================================
#						Here we will have the go to functions
#===================================================================================
	function goToSubmitPage(){
		if (isset($_POST['new'])){
			header('Location: http://cs.okstate.edu/~mwhelan/Submit_User.html');
		}
	}
	function goToLoginPage(){
		if (isset($_POST['old'])){
			header('Location: http://cs.okstate.edu/~mwhelan/User_Login.php');
		}
	}
	function goToadminPage(){
		if (isset($_POST['admin'])){
			header('Location: http://cs.okstate.edu/~mwhelan/Admin_Login.html');
		}
	}
?>
</html>