<?php 
	session_start();
	
	include_once 'timesession.php';
?>
	
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" type="text/css" href="style.css">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		
		<?php
		
			$cookie_name = "CookieSite";
			
			if(count($_COOKIE) > 0) {
				if(!isset($_COOKIE[$cookie_name])) {
					echo '<script> alert("Pressing OK you accept the use of cookies and javascript"); </script>';	
					$cookie_value = "on";
					setcookie($cookie_name, $cookie_value, time() + (60 * 60 * 24 * 365));
				}
			}else{
				header("Location: notcoockies.php");
			}
				
			if(strtolower( $_SERVER['HTTPS']) != "on"){
				header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
			
		?>
	
		<title>World Tour</title>

	</head>

	<body class="bg">
		
		
		<div  class="sidenav" id="menu">
			
		  <a class="nav-link" href="index.php">
			<button type="button" class="btn btn-warning">Home</button>
		  </a>
			  
			<?php
				if (isset($_SESSION['user'])) {

                    echo '
						<a class="nav-link" href="userpage.php">
							<button type="button" class="btn btn-info">
								User
							</button>					
						</a>
						
						<a class="nav-link">
									<form method="POST" action="logout.php">
										<button type="submit" class="btn btn-danger" name="logout"> Logout </button>
									</form>
						</a>';

                }
                else{
				    echo '
					
						<a class="nav-link" href="signin.php">
							<button type="button" class="btn btn-success">
									Sign In
							</button>
						</a>
						
						
						<a class="nav-link">		
							
						<div>
							<h5 style="color:white; text-align: center;">Login</h5>
							<form method="POST" action="login.php">
							  <div class="form-group">
								<input type="email" class="form-control" name="email" id="InputEmail" aria-describedby="emailHelp" placeholder="Enter email">
							  </div>
							  
							  <div class="form-group">
								<input type="password" class="form-control" name="password" id="InputPassword" placeholder="Password">
							  </div>
							  <button type="submit" class="btn btn-primary" name="login">Login</button>
							</form>
						 </div>
						
							
						</a>
							 
						';
                }
			?>
			
		</div>
		
		<h1 style="color:white">World Tour</h1>	
			
		<div class="container">
			<div class="row align-items-center">

				<div class="col">
				</div>

				<div class="col-5 align-self-center">
					<h3 style="color:green" class="alert alert-success">All Destinations</h3>
					<ul class="list-group">
						<?php 
							include_once 'printdest.php';
						?>
					</ul>
				</div>

				<div class="col">
				</div>

			</div>
		</div>
			
		<script src="js/bootstrap.min.js"></script>
		
	</body>

</html>