<!DOCTYPE HTML>
<html>
    <head>
        <title>QBnB</title>
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">

        <!-- Bootstrap Vertical Nav -->
        <link rel="stylesheet" href="stylesheets/loginPage.css">
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
	$_SESSION['email']=null;
	session_destroy();
}
 ?>
 
 
 <?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['email'])){
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
        $query = "SELECT email, password, admin FROM member WHERE email=? AND password=?";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)){
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ss", $_POST['email'], $_POST['password']);

        // Execute the query
		$stmt->execute();
 
		/* resultset */
		$result = $stmt->get_result();

		// Get the number of rows returned
		$num = $result->num_rows;
		//echo "here";
		if($num>0){
			//If the username/password matches a user in our database
			//Read the user details
			$myrow = $result->fetch_assoc();
			//Create a session variable that holds the user's id
			$_SESSION['email'] = $myrow['email'];

            //Check if the user is an admin and set the session['admin'] to 0 or 1
            if ($myrow['admin'] != 0){
                $_SESSION['admin']=1;
                //echo $_SESSION['admin'];
            }

            else
                $_SESSION['admin']=0;
                header("Location: /QBnB/profile.php");
			//Redirect the browser to the profile editing page and kill this page.
			//header("Location: /QBnB/profile.php");
			//echo "Successful Login";
			die();
		} else {
			//If the username/password doesn't matche a user in our database
			// Display an error message and the login form
			//echo "Failed to login";
		}
		} else {
			//echo "failed to prepare the SQL";
		}
 }
 if(isset($_POST['signupBtn'])){
 
    // include database connection
    include_once 'config.php'; 

	
	// SELECT query
        $query = "Insert into member (email, password, admin, phone_num, year, degree_name, faculty_name, Name) values (?, ?, 0, ?, ?, ?, ?, ?)";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)){
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("sssisss", $_POST['signupEmail'],$_POST['signupPassword'],$_POST['phone_num'], $_POST['gradYear'], $_POST['degree_name'], $_POST['faculty_name'], $_POST['signupName']);

         
        // Execute the query
		if($stmt->execute()){
			//if the user creates a new user we should update the session to include their information
            //load the information so we know what to load in the profile page 
             /*$query = "SELECT email, password FROM member WHERE email=" . echo $_POST['signupEmail'] . " AND password=" . $_POST['signupPassword'] ;

            // prepare query for execution
            if($getNewUser = $con->prepare($query)){
                $result = $getNewUser->result();
                $newUserInfo = $result->fetch_assoc();
                $_SESSION['email'] = $newUserInfo['email'];*/
                $_SESSION['admin']=0;
                $_SESSION['email'] = $_POST['signupEmail'];
    			header("Location: /QBnB/profile.php");
    			//echo "user successfully created";
    			die();
            //}
		} else {
			//If the username/password doesn't matche a user in our database
			// Display an error message and the login form
			//echo "Failed to create user";
		}
		} else {
			//echo "failed to prepare the SQL";
		}
 }
 
?>
<div class="row" id = "header">
    <div class = "col-md-4" id = "title">
        QBnB
    </div>
    <div class = "col-md-8">
        <form  name='login' id='login' action='index.php' method='post'>
            <div class="col-md-3"> <span style="color: #FFFFFF"> Email </span>
                <input class = "form-control" type='text' name='email' id='email' placeholder = "Email" />
            </div>
            <div class = "col-md-3"> <span style="color: #FFFFFF"> Password </span>
                <input class = "form-control" type='password' name='password' id='password' placeholder = "Password" />
            </div>
            <div class = "col-md-1">
                <input class="btn btn-default" type='submit' id='loginBtn' name='loginBtn' value='Log In' />
            </div>
        </form>
    </div>
</div>
<!-- dynamic content will be here -->
<row>
    <h2> Join the Tri-Colour Community </h2>
</row>
<row>
    <h4> Sign-up Below! </h4>
</row>
    <form class="form-horizontal" name='signup' id='signup' action='index.php' method='post'>
        <table>
            <tr>
                <td>Name</td>
                <td><input class = "form-control" type='text' name='signupName' id='signupName' /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input class = "form-control" type='text' name='signupEmail' id='signupEmail' /></td>
            </tr>
            <tr>
                <td>Password</td>
                 <td><input class = "form-control" type='password' name='signupPassword' id='signupPassword' /></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><input class = "form-control" type='value' name='phone_num' id='phone_num' /></td>
            </tr>
            <tr>
                <td>Graduation Year</td>
                 <td><input class = "form-control" type='value' name='gradYear' id='gradYear' /></td>
            </tr>
            <tr>
                <td>Degree Name</td>
                <td><select name='degree_name' id='degree_name'>
                        <option value="BComm">BComm</option>
                        <option value="BEng">BEng</option>
                        <option value="BSc">BSc</option>
                        <option value="MSc">MSc</option>
                        <option value="PhD">PhD</option>
                    </select></td>
            </tr>
            <tr>
                <td>Faculty Name</td>
                 <td><select name='faculty_name' id='faculty_name'>
                        <option value="Arts & Science">Arts & Science</option>
                        <option value="Commerce">Commerce</option>
                        <option value="Engineering & Applied Science">Engineering & Applied Science</option>
                        <option value="Law">Law</option>
                    </select></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input class="btn btn-primary" type='submit' id='signupBtn' name='signupBtn' value='Sign Up' /> 
                </td>
            </tr>
        </table>
    </form>
</body>
</html>