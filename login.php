<?php

session_start();

if(isset($_POST['login'])){
	
	$dbserver = "localhost";
	$dbusername = "s255185";
	$dbpassword = "ngskyeds";
	$dbname = "s255185";
	
	//connect to the database
	$conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $dbname);
	
	if(!$conn){
		header("Location: index.php?connfailed");
		exit();
	}
	
	//sanitize the input string from ddangerous characters
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	
	//check if a field is empty
	if(empty($email) || empty($password)){
		mysqli_close($conn);
		header("Location: index.php?login=empty");
		exit();
	}
	else{
		
		//select the user from the table
		$query = "SELECT * FROM member WHERE name='" . $email . "'";
		
		try {
			mysqli_autocommit($conn, false);
			
			$res = mysqli_query($conn, $query);
			
			if(!$res){
			throw new Exception($conn->error);
			}
			
			mysqli_commit($conn);
		}
		catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res);
			header("Location: index.php?login=queryfailed");
			exit();
		}
		
		$rescheck = mysqli_num_rows($res);
		
		//if the number of rows are minor of 1 there is no user with this email
		if($rescheck < 1){
			mysqli_close($conn);
			mysqli_free_result($res);
			header("Location: index.php?login=notpresent");
			exit();
		}
		else{
			if($row = mysqli_fetch_assoc($res)){
				//compare the password
				$hashpwdcheck = password_verify($password, $row['password']);
				
				if($hashpwdcheck == true){
					$_SESSION['user'] = $row['name'];
					$_SESSION['time'] = time();
					mysqli_close($conn);
					mysqli_free_result($res);
					header("Location: userpage.php?login=success");
					exit();
				}
				else{
					mysqli_close($conn);
					mysqli_free_result($res);
					header("Location: index.php?login=wrongpwd");
					exit();
				}
			}
		}
	}
	
}
else{
	mysqli_close($conn);
	header("Location: index.php?login=notpost");
	exit();
}

?>
