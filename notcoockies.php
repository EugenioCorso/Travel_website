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
		
		<?php
		
			if($_SERVER['HTTPS'] != "on"){
				header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
			
			if(count($_COOKIE) > 0) {
				header("Location: index.php");
			}
			
		?>
	
		<title>Error</title>

	</head>

	<body>
		
		
		<b>Activate coockies to use the site</b>
		
		
	</body>

</html>