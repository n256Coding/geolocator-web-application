<?php
	session_start();
	if(!isset($_SESSION['uname']))
	{
		header("location: index.php");
    	exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Index Geo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="getLoc.js"></script>
  
  <link href="style.css" rel="stylesheet"> 
</head>
<body style="height:100px;">

<div class="container-fluid" style="background-color:#0A0;color:#fff;height:200px;">
  <h1>GeoLocation View</h1>
  <h3>Record your history</h3>
  <p>Login to start</p>
  <p>The navbar is attached to the top of the page after you have scrolled a specified amount of pixels.</p>
</div>

<div class="navbar navbar-inverse" data-spy="affix" data-offset-top="197">
  <ul class="nav navbar-nav">
	<li><a href="index.php">Home</a></li>
    <li><a href="contactus.html">Contact Us</a></li>
    <li><a href="aboutus.html">About Us</a></li>
    <li><a href="faq.html">FAQs</a></li>
  </ul>
</div>

<!--Main Div-->
<div class="container-fluid" style="height:700px">

<div align="right"> <!--Login Contraller Div-->
	<form method="POST">
		<input type="submit" name="logout" value="logout" class="btn btn-danger">
	</form>

<?php
	echo "<h4 class='h4'>".$_SESSION['uname']."</h4>";
	if(isset($_POST['logout']))
	{
		$_SESSION['logged'] = 0;
		unset($_SESSION['uname']);
		header("location: Index.php");
    	exit();
	}
?>
</div> <!--Login Contraller Div CLOSE-->

<div align="left" style="width:200px; background-color:#FFFFCC; height:600px; float:left"> <!--Left Side Pane Div-->
<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "login";
	
	$uName = $_SESSION['uname'];	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if(!$conn)
		die("Connection faild: " . mysqli_connect_error());
	
	$sql = "SELECT * FROM $uName;";
	$result = mysqli_query($conn,$sql);	
	echo "<form method='post'>";									
	if($result)
	{
		echo "<h4><strong>User History</strong></h4>";
		echo "<ul>";
		while($row = $result->fetch_assoc())
			echo "<input type='radio' name='data' value=".$row['date']."/><span>"." ".$row['date']."</span><br>";
		echo "</ul>";
		echo "<input type='submit' value='Search' name='search2'>";
	}
	else
		echo "No Data";
	echo "</form>";
?>
</div> <!--Left Side Pane Div CLOSE-->

<div id="mapholder" style="margin-left:100px;"><center>Click Show my location to get Location on Google map</center></div> <!--Google Map holder Div-->

<div id="save" class="well-sm"><!--Record Save Div-->
	<form method="POST">
    	<input type="submit" value="Save" name="save" class="btn btn-primary">
        <input type="button" value="Show My Location" onClick="getLocation()" class="btn btn-primary">
    </form>
</div><!--Record Save Div CLOSE-->
<?php
	if(isset($_POST['save']))
	{
		if(isset($_COOKIE['latitude']))
		{
			$lat = $_COOKIE['latitude'];
			$lon = $_COOKIE['longitude'];
			
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "login";
	
			$uName = $_SESSION['uname'];	
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if(!$conn)
				die("Connection faild: " . mysqli_connect_error());
	
			$sql = "INSERT INTO $uName (latitude, longitude) VALUES ($lat, $lon);";
			mysqli_query($conn,$sql);
			echo "<script>alert('Location Snapshot Created Succesfully');</script>";
		}
		else
			echo "<script>alert('No Location data found!!');</script>";
	}
?>

<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<?php
	if(isset($_POST['search2']))
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "login";
	
		$uName = $_SESSION['uname'];
		$date = $_POST['sDate'];
		if($date == "" || $date == null)
			echo "<script>alert('Please Enter Date and Time');</script>";
		else
		{
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if(!$conn)
				die("Connection faild: " . mysqli_connect_error());
	
			$sql = "SELECT * FROM $uName WHERE date = '$date';";
			$result = mysqli_query($conn,$sql);
			if(!$result)
				echo "<script>alert('Location Could not Find');</script>";
			else
			{
				while($row = $result->fetch_assoc())
				{
					$_COOKIE['latitude'] = $row['latitude'];
					$_COOKIE['longitude'] = $row['longitude'];
//					echo "Longitude :".$_COOKIE['longitude']."<br>";
//					echo "Latitude  :".$_COOKIE['latitude'];
				}
				echo "<script>alert('Location Found, Click Show to see in map');</script>";
/*				echo "<script> showCook();</script>";*/
			}
		}
	}
?>
<script>
  		function showCook()
		{
			var lat = <?php echo json_encode($_COOKIE['latitude']); ?>;
			var lon = <?php echo json_encode($_COOKIE['longitude']); ?>;
		//	alert(lat);
		//	var lat = 40;
		//	var lon = 72;
    		latlon = new google.maps.LatLng(lat, lon);
    		mapholder = document.getElementById('mapholder');
    		mapholder.style.height = '300px';
    		mapholder.style.width = '700px';

    		var myOptions = {
    		center:latlon,zoom:14,
    		mapTypeId:google.maps.MapTypeId.ROADMAP,
    		mapTypeControl:false,
    		navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    		}   
    		var map = new google.maps.Map(document.getElementById("mapholder"), myOptions);
    		var marker = new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
		}
 </script>

<div class="well well-lg" style="margin-left:200px;"><!--Search Div-->
<form method="POST">
	<h3 class="h3">Search History</h3>
	<input type="text" name="sDate" class="form-control" style="width:300px;"><br>
    <input type="submit" name="search" Value="Search" Class="btn btn-success">
    <input type="button" name="show" value="show" onClick="showCook()" Class="btn btn-info">
</form>
<br>
<p><strong>Note: </strong>Type date and time form User History and click search (EX: copy & paste)</p>
</div><!--Search Div CLOSE-->
    
    
    
    
<!--Main Div CLOSE-->    
<div>


</div>
</div>


<script>
//	window.onload=getLocation;
//	setInterval( [you code or function call][, time interval in milliseconds] );
</script>
</body>
</html>
