<!DOCTYPE HTML>
<html> 
	<head> 
		<title> QBnB </title>
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">

        <!-- Bootstrap Vertical Nav -->
        <link rel="stylesheet" href="stylesheets/bootstrap-vertical-menu.css">
	</head>
	<body>
	<?php
  	//Create a user session or resume an existing one
 	session_start();
 	?>
<?php
 //process the search based on the criteria
$searchResults = "";
  //$_SESSION['email'] = "12mjs17@queensu.ca"; //This is for testing. Remove later *********************************************
 if(isset($_SESSION['email']) && isset($_POST['searchBtn'])){
  // include database connection
    include_once 'config.php'; 

    $whereAddress = "address!=?";
    if ($_POST['address'])
    	$whereAddress = "address=?";
    $whereMinPrice = "price!=?";
    if ($_POST['minPrice'])
    	$whereMinPrice = "price>=?";
    $whereMaxPrice = "price!=?";
    if ($_POST['maxPrice'])
    	$whereMaxPrice = "price<=?";
    $whereDistrict = "district_name!=?";
    if ($_POST['district_name'])
    	$whereDistrict = "district_name=?";
    $whereRooms = "rooms!=?";
    if ($_POST['rooms'])
    	$whereRooms = "rooms=?";
	$whereType = "type!=?";
    if ($_POST['type'])
    	$whereType = "type=?";

    $dCond = $_POST['district_name'];
    $tCond =  $_POST['type'];
    echo $dCond . "\n";
    echo $tCond . "\n";
    //Inititalize Variables
    $query = "";
    $stmt = "";

    if ($dCond === "Any" && $tCond === "Any") {
    	$query = "SELECT * FROM Property Where " . $whereAddress . " AND  " . $whereMinPrice . " AND " . $whereMaxPrice . " AND " . $whereRooms;
	 	echo $query;
	    $stmt = $con->prepare($query);  
	    $stmt->bind_param('siii',  $_POST['address'], $_POST['minPrice'], $_POST['maxPrice'], $_POST["rooms"]);
	}
	else if ($tCond != "Any"){
		$query = "SELECT * FROM Property Where " . $whereAddress . " AND  " . $whereMinPrice . " AND " . $whereMaxPrice . " AND " . $whereRooms
		. " AND " . $whereType;
	 	//echo $query;
	    $stmt = $con->prepare($query);  
	    $stmt->bind_param('siiis',  $_POST['address'], $_POST['minPrice'], $_POST['maxPrice'], $_POST["rooms"], $_POST["type"]);
	}
	else if ($dCond != "Any"){
		$query = "SELECT * FROM Property Where " . $whereAddress . " AND  " . $whereMinPrice . " AND " . $whereMaxPrice . " AND " . $whereDistrict
		. " AND " . $whereRooms;
		echo "here";
	 	//echo $query;
	    $stmt = $con->prepare($query);  
	    $stmt->bind_param('siisi',  $_POST['address'], $_POST['minPrice'], $_POST['maxPrice'], $_POST["district_name"], $_POST["rooms"]);
	}
	else {
		 $query = "SELECT * FROM Property Where " . $whereAddress . " AND  " . $whereMinPrice . " AND " . $whereMaxPrice . " AND " . 
    	$whereDistrict . " AND " . $whereRooms . " AND " . $whereType;
 		//echo $query;
    	$stmt = $con->prepare($query);  
    	$stmt->bind_param('siisis',  $_POST['address'], $_POST['minPrice'], $_POST['maxPrice'], $_POST['district_name'], $_POST["rooms"], $_POST["type"]);
	}
	// Execute the query
    if($stmt->execute()){
        echo "Search was successful. <br/>";
		$searchResults = $stmt->get_result();
	}
 	}
 	else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
}

 ?>
<nav class = "header">
  <li class = "navp"><a href="/QBnB/index.php">Home</a></li>
  <li class = "navp"><a href="/QBnB/profile.php">Profile</a></li>
  <li class = "navp"><a href="/QBnB/addProperty.php">Become a host</a></li>
  <li class = "navp"><a href="/QBnB/search.php">Find a Place</a></li>
  <li class = "navp"><a href="/QBnB/about.php">About</a></li>
  <li class = "navp"><a href="/QBnB/index.php?logout=1">Log Out</a></li>
</nav>
	<h1> Search Property Page </h1>
	<!--List the criteria
	//Price range-->
	<div class="col-md-6">
		<form name='searchProperty' id='searchProperty' action='search.php' method='post'>
	        <table border='0'>
	            <tr>
	                <td>Address</td>
	                <td><input type='text' name='address' id='address'  /></td>
	            </tr>
	            <tr>
	                <td> Min Price</td>
	                 <td><input type='value' name='minPrice' id='minPrice' /></td>
	            </tr>
	            <tr>
	                <td> Max Price</td>
	                 <td><input type='value' name='maxPrice' id='maxPrice' /></td>
	            </tr>
	            <tr>
	                <td>District</td>
	                <td>
		                <select name='district_name' id='district_name'>
			                <option text="Any">Any</option>
			                <option text="Entertainment">Entertainment</option>
			                <option text="Downtown">Downtown</option>
			                <option text="University">University</option>
		            	</select>
	               	</td>
	            </tr>
	             <tr>
	                <td>Rooms</td>
	                <td><input type='value' name='rooms' id='rooms'/></td>
	            </tr>
	             <tr>
	                <td>Room(s) Type</td>
	                <td>
		                <select name='type' id='type'>
			                <option text="Any">Any</option>
			                <option text="Bedroom">Bedroom</option>
			                <option text="Apartment">Apartment</option>
			                <option text="University">University</option>
			                <option text="Flat">Flat</option>
			                <option text="House">House</option>
		            	</select>
	               	</td>
	            </tr>
	        </table>
	        <input type='submit' name='searchBtn' id='searchBtn' value='Search' /> 
	    </form>
	</div>
	<div class="col-md-6">
		<h2> 
			Search Results
		</h2> 
        <table border='0' style="width:100%">
            <tr>
                <td>Address</td>
                <td>Price</td>
                <td>District</td>
                <td>Number of Rooms</td>
                <td>Room Type</td>
            </tr>
            <?php
            	if ($searchResults != ""){
	            	$address = array();
	            	$price = array();
	            	$district = array();
	            	$rooms = array();
	            	$type = array();
	            	while ($row_results = $searchResults->fetch_assoc()) {
			            array_push($address, ($row_results['address']));
			            array_push($price, ($row_results['price']));
			            array_push($district, ($row_results['district_name']));
			            array_push($rooms, ($row_results['rooms']));
			            array_push($type, ($row_results['type']));
	        		}
	        		/*<a href="http://example.com">
					    <div style="height:100%;width:100%">
					      hello world
					    </div>
					  </a>*/
	        		for ($i = 0; $i < count($address); $i++){  
		        		echo "<tr>
		        			<td> 
		        				<a href=/QBnB/viewProperty.php?propertyAddress=" . urlencode($address[$i]) . "> 
		        				" . $address[$i] . "
		        			</td>
		        			<td>
		        				" . $price[$i] . "
		        			</td>
		        			<td>
		        				" . $district[$i] . "
		        			</td>
		        			<td>
		        				" . $rooms[$i] . "
		        			</td> 
		        			<td>
		        				" . $type[$i] . "
		        			</td>     
		        		</tr>";
		        	}
		        }
           ?>
            <div id="searchResuls" name="searchResults">
            </div>
        </table>
	</div>



	</body>
</html>