<?php
    if($_SESSION['admin']){
    echo '<nav class = "header">
      <li class = "navp"><a href="/QBnB/index.php">Home</a></li>
      <li class = "navp"><a href="/QBnB/admin.php">Administration</a></li>
      <li class = "navp"><a href="/QBnB/profile.php">Profile</a></li>
      <li class = "navp"><a href="/QBnB/addProperty.php">Become a host</a></li>
      <li class = "navp"><a href="/QBnB/search.php">Find a Place</a></li>
      <li class = "navp"><a href="/QBnB/about.php">About</a></li>
      <li class = "navp"><a href="/QBnB/index.php?logout=1">Log Out</a></li>
    </nav>';
    }
    else{
    echo '<nav class = "header">
      <li class = "navp"><a href="/QBnB/index.php">Home</a></li>
      <li class = "navp"><a href="/QBnB/profile.php">Profile</a></li>
      <li class = "navp"><a href="/QBnB/addProperty.php">Become a host</a></li>
      <li class = "navp"><a href="/QBnB/search.php">Find a Place</a></li>
      <li class = "navp"><a href="/QBnB/about.php">About</a></li>
      <li class = "navp"><a href="/QBnB/index.php?logout=1">Log Out</a></li>
    </nav>';}
?>