<!DOCTYPE HTML>
<?php
 session_start();
  //Create a user session or resume an existing one
 ?>
<html>
    <head>
        <title>Welcome to mysite</title>
  
    </head>
<body>

<!-- dynamic content will be here -->
 <form name='login' id='login' action='index.php' method='post'>
    <table border='0'>
        <tr>
            <td>Username</td>
            <td><input type='text' name='username' id='username' /></td>
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

</body>
</html>