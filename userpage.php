<?php 
	session_start();
	
	include_once 'timesession.php';
	
	if (!isset($_SESSION['user'])) {
		header("Location: index.php?notlogged");
		exit();
	}
	
	
	if($_SERVER['HTTPS'] != "on"){
		header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
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
		
		<title>Personal page</title>

	</head>

	<body class="bg">
	
		
		<div  class="sidenav" id="menu">
			
			<a class="nav-link" href="index.php">
				<button type="button" class="btn btn-warning">Home</button>
			</a>
			  
			<a class="nav-link">
				<form method="POST" action="logout.php">
					<button type="submit" class="btn btn-danger" name="logout"> Logout </button>
				</form>
			</a>
			
		</div>
		
		<h1 style="color:white">
			Welcome
			<?php 
				echo $_SESSION['user'];
			?>
		</h1>	
		
		<div class="container">
			<div class="row align-items-center">

				<div class="col">
				</div>
				
				<div class="col-5 align-self-center">
					<h3 style="color:green" class="alert alert-success">All Destinations</h3>
					<ul class="list-group">
						<?php 
							include_once 'usertable.php';
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