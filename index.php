<?php
	session_start();
	if(isset($_SESSION['logged']) && $_SESSION['logged']==1)
	{
		header("location: sIndex.php");
    	exit();
	}
?>




<!DOCTYPE html>
<html>
<head>
  <title>Geolocation System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link href="style.css" rel="stylesheet">
  
</head>
<body>

<div class="container-fluid" style="background-color:#0A0;color:#fff;height:200px;">
  <h1>GeoLocation View</h1>
  <h3>Record your history</h3>
  <p>Login to start</p>
  <p>The navbar is attached to the top of the page after you have scrolled a specified amount of pixels.</p>
</div>

<nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="197">
  <ul class="nav navbar-nav">
 	<li><a href="index.php">Home</a></li>
    <li><a href="contactus.html">Contact Us</a></li>
    <li><a href="aboutus.html">About Us</a></li>
    <li><a href="faq.html">FAQs</a></li>
  </ul>
</nav>

<!--Main Div-->
<div class="container-fluid" style="height:750px; background-image:url(images/back.jpg); background-repeat:repeat;">

	<div style="float:left; margin:125px;"><!--Login Div-->
      <h2 align="center">Login</h2><br>
      	<form method="post">
  		<table align="center">
        	<tr>
 <!--           	<td align="right">Username :</td>
                <td><input type="text" name="uname" id="uname"></td>       -->
                <div class="form-group">
      				<label for="usr">Username:</label>
      				<input type="text" class="form-control" id="usr" name="uname">
    			</div>
            </tr>
            <tr>
 <!--           	<td align="right">Password :</td>
                <td><input type="text" name="pwd" id="pwd"></td>     -->
                <div class="form-group">
      				<label for="pwd">Password:</label>
      				<input type="password" class="form-control" id="pwd" name="pwd">
    			</div>
            </tr>
            <tr>
            	<td></td>
                <td><input type="submit" name="login" value="Login" class="btn btn-primary"></td>
            </tr>
        </table><br>
      </form>
      			<?php
					if(isset($_POST['login']))
					{
						$servername = "localhost";
						$username = "root";
						$password = "";
						$dbname = "login";

						$uName = $_POST["uname"];
						$uPwd = $_POST["pwd"];
	
						if($uName == '' || $uName == null || $uPwd == '' || $uPwd == null)
							echo "<li style='color:red'>Enter Both username and Password</li>";
						else
						{
							$conn = mysqli_connect($servername, $username, $password, $dbname);
							if(!$conn)
							{
								die("Connection faild: ".mysqli_error());
							}
		
							$sql = "SELECT * FROM $uName;";
								$result = mysqli_query($conn,$sql);
													
							if($result)
							{
								$sql = "SELECT pwd FROM users WHERE username = '$uName';";
									$resultP = mysqli_query($conn,$sql);
									while($row = $resultP->fetch_assoc())
									{
										$resPwd = $row["pwd"];
									}
							
								if($resPwd == $uPwd)
								{
									$_SESSION['uname'] = $uName;
									$_SESSION['logged'] = 1;
									header("location: sIndex.php");
    								exit();
								}
								else
									echo "<li style='color:red;'>Invalid Password, Try Again</li>";
							}
							else
								echo "<li style='color:red;'>You don't have an Account<br>Click signup to create an account free</li>";
						}
					}
				?>
	</div><!--Login Div Close-->
    
    <div style="float:left; margin:125px;"><!--Sign In Div-->
    	<h2 align="center">Sign Up</h2><br>
    	<form method="post">
  		<table align="center">
        	<tr>
<!--            	<td align="right">Username :</td>
                <td><input type="text" name="Nuname" id="Nuname"></td>           -->
                <span style="color:#CC3300;">*</span><label for="Nuname">Firstname:</label>
      			<input type="text" class="form-control" id="Nuname" name="Nuname">
            </tr>
            <tr>
<!--            	<td align="right">Password :</td>
                <td><input type="text" name="Npwd" id="Npwd"></td>               -->
                <span style="color:#CC3300;">*</span><label for="Nlname">Lastname:</label>
      			<input type="text" class="form-control" id="Nlname" name="Nlname">
            </tr>
            <tr>
<!--           		<td align="right">Lastname :</td>
                <td><input type="text" name="Nlname" id="Nlname"></td>           -->
                <span style="color:#CC3300;">*</span><label for="Npwd">Password:</label>
      			<input type="password" class="form-control" id="Npwd" name="Npwd">
            </tr>
            <tr>
                <br>
                <input type="submit" name="signup" value="Sign Up" class="btn btn-success">
            </tr>
        </table><br>
      </form>
      <?php
					if(isset($_POST['signup']))
					{
						$servername = "localhost";
						$username = "root";
						$password = "";
						$dbname = "login";

						$uName = $_POST["Nuname"];
						$uPwd = $_POST["Npwd"];
						$uLname = $_POST["Nlname"];
	
						if($uName == '' || $uName == null || $uPwd == '' || $uPwd == null || $uLname == '' || $uLname == null)
							die("<li style='color:red;'>Fill All Fields</li>");
						
		
						$conn = mysqli_connect($servername, $username, $password, $dbname);
						if(!$conn)
							die("Connection faild: " . mysqli_connect_error());
		
						$sql = "SELECT * FROM $uName";
							$result = mysqli_query($conn,$sql);
							if($result)
							{
								echo "<li style='color:red;'>This username already excist, Try another name</li>";
							}
							else
							{
								$sql = "INSERT INTO users (username, pwd, lname) VALUES ('$uName', '$uPwd', '$uLname');";
								$result = mysqli_query($conn,$sql);
								$sql = "CREATE TABLE $uName(
												latitude double,
												longitude double,
												date datetime NOT NULL DEFAULT NOW()
												);";
								mysqli_query($conn,$sql);
								echo "<script>alert('Your Username is '+'$uName');</script>";
								echo "Your Account has created, <br>Your Username is <strong>$uName</strong>.";
							}
					}
				?>
    </div><!--Sign in Div Close-->
    
<!--Main Div Close-->    
<div>

</div>
</div>

</body>
</html>
