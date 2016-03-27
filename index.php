<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to mysite</title>
  
    </head>
<body>

 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
 //check if the user clicked the logout link and set the logout GET parameter
if(isset($_GET['logout'])){
	//Destroy the user's session.
	$_SESSION['id']=null;
	session_destroy();
}
 ?>
 
 
 <?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['id'])){
	//Redirect the browser to the profile editing page and kill this page.
	header("Location: profile.php");
	die();
}
 ?>
 
 <?php

 
//check if the login form has been submitted
if(isset($_POST['loginBtn'])){
 
    // include database connection
    include_once 'config.php'; 
	
	// SELECT query
        $query = "SELECT email, password FROM member WHERE email=? AND password=?";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)){
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ss", $_POST['email'], $_POST['password']);


         
        // Execute the query
		$stmt->execute();
 
		/* resultset */
		$result = $stmt->get_result();

		// Get the number of rows returned
		$num = $result->num_rows;;
		//echo "here";
		if($num>0){
			//If the username/password matches a user in our database
			//Read the user details
			$myrow = $result->fetch_assoc();
			//Create a session variable that holds the user's id
			$_SESSION['email'] = $myrow['email'];
			//Redirect the browser to the profile editing page and kill this page.
			header("Location: /QBnB/profile.php");
			echo "Successful Login";
			die();
		} else {
			//If the username/password doesn't matche a user in our database
			// Display an error message and the login form
			echo "Failed to login";
		}
		} else {
			echo "failed to prepare the SQL";
		}
 }
 if(isset($_POST['signupBtn'])){
 
    // include database connection
    include_once 'config.php'; 
	
	// SELECT query
        $query = "Insert into member values (?, ?, ?, ?, ?, ?)";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)){
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        //$stmt->bind_Param("ssiiss", $_POST['signupEmail'], $_POST['signupPassword'],$_POST['phone_num'], $_POST['gradYear'], $_POST['degree_name'], $_POST['faculty_name']);
        $first = "jdawg@queensu.ca";
        $second = "1234";
        $third = 9999999999;
        $fourth = 2017;
        $fifth = "BEng";
        $sixth = "Engineering & Applied Science";
        $stmt->bind_Param("ssiiss", $first, $second,$third, $fourth, $fifth, $sixth);


         
        // Execute the query
		if($stmt->execute()){
			//If the username/password matches a user in our database
			//Read the user details
			//$myrow = $result->fetch_assoc();
			//Create a session variable that holds the user's id
			//$_SESSION['id'] = $myrow['id'];
			//Redirect the browser to the profile editing page and kill this page.
			header("Location: /QBnB/profile.php");
			echo "user successfully created";
			die();
		} else {
			//If the username/password doesn't matche a user in our database
			// Display an error message and the login form
			echo "Failed to create user";
		}
		} else {
			echo "failed to prepare the SQL";
		}
 }
 
?>

<!-- dynamic content will be here -->
<h2> QBnB </h2>
 <form name='login' id='login' action='index.php' method='post'>
    <table border='0'>
        <tr>
            <td>Username</td>
            <td><input type='text' name='email' id='email' /></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='password' name='password' id='password' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='loginBtn' name='loginBtn' value='Log In' /> 
            </td>
        </tr>
    </table>
</form>
<form name='signup' id='signup' action='index.php' method='post'>
    <table border='0'>
        <tr>
            <td>Email</td>
            <td><input type='text' name='signupEmail' id='signupEmail' /></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='password' name='signupPassword' id='signupPassword' /></td>
        </tr>
        <tr>
            <td>Phone Number</td>
            <td><input type='value' name='phone_num' id='phone_num' /></td>
        </tr>
        <tr>
            <td>Graduation Year</td>
             <td><input type='value' name='gradYear' id='gradYear' /></td>
        </tr>
        <tr>
            <td>Degree Name</td>
            <td><input type='text' name='degree_name' id='degree_name' /></td>
        </tr>
        <tr>
            <td>Faculty Name</td>
             <td><input type='text' name='faculty_name' id='faculty_name' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='signupBtn' name='signupBtn' value='Sign Up' /> 
            </td>
        </tr>
    </table>
</form>

</body>
</html>