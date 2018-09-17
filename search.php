<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "login";
	
		$uName = $_SESSION['uname'];
		$date = $_POST['sDate'];	
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if(!$conn)
			die("Connection faild: " . mysqli_connect_error());
	
		$sql = "SELECT * FROM $uName WHERE date = '$date';";
		$result = mysqli_query($conn,$sql);
		while($row = $result->fetch_assoc())
		{
			$_COOKIE['latitude'] = $row['latitude'];
			$_COOKIE['longitude'] = $row['longitude'];
			echo "Longitude :".$_COOKIE['longitude']."<br>";
			echo "Latitude  :".$_COOKIE['latitude'];
		}
?>