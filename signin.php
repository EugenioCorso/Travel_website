<?php 
	session_start();
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
		
		<title>Sig In</title>
		
		<script> 
			
			function check(){
				var pwd = document.getElementById('InputPassword').value;
				var email = document.getElementById('InputEmail').value;
				var patt = new RegExp(".*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*");
				var res = patt.test(pwd);
				
				if(!res || (email == "")){
					document.getElementById("signin").setAttribute("disabled", "true"); 
				}
				else{
					document.getElementById("signin").removeAttribute("disabled", "false");
				}
				
			}
			
		</script>
		
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

	</head>

	<body class="bg" id="signinbg">
	
		<div  class="sidenav" id="menu">
			
		  <a class="nav-link" href="index.php">
			<button type="button" class="btn btn-warning">Home</button>
		  </a>
			
		</div>
		
		<div class="container">
			<div class="row align-items-center">

				<div class="col">
				</div>

				<div class="col-5 align-self-center">
				
				<h1>Sign In</h1>
				
				<form id="signinform" action="./signinscript.php" method="POST">
				  <div class="form-group">
					<label for="InputEmail">Email address</label>
					<input type="email" class="form-control" name="email" id="InputEmail" aria-describedby="emailHelp" placeholder="email@example.com" onkeyup="check()">
					<small id="emailHelp" class="form-text text-muted">Insert a valid email address</small>
				  </div>
				  <div class="form-group">
					<label for="InputPassword">Password</label>
					<input type="password" class="form-control" name="password" id="InputPassword" placeholder="Password" onkeyup="check()">
					<small class="form-text text-muted">
					Your password must contain at least a lowercase alphabetic character, and at least another character which must 
					be either an uppercase alphabetic character or a digit
					</small>
				  </div>
				  <button type="submit" id="signin" class="btn btn-primary" name="signin" disabled>Sign In</button>
				</form>
				</div>

				<div class="col">
				</div>

			</div>
		</div>
		
		<script src="js/bootstrap.min.js"></script>
		
	</body>

</html>