<?php

session_start();

if(isset($_POST['delete'])){
	
	$dbserver = "localhost";
	$dbusername = "s255185";
	$dbpassword = "ngskyeds";
	$dbname = "s255185";
	
	//connect to the database
	$conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $dbname);
	
	if(!$conn){
		header("Location: userpage.php?connfailed");
		exit();
	}
	
	//select the user to take the start and the destination
	$query = "SELECT * FROM member WHERE name = '" . $_SESSION['user'] . "' FOR UPDATE;";	
		
	try {
		mysqli_autocommit($conn, false);
		
		$res = mysqli_query($conn, $query);
		
		if(!$res){
		throw new Exception($conn->error);
		}
		
	}
	catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res);
		header("Location: userpage.php?book=queryfailed0");
		exit();
	}
	
	//store the start and the destination booked by the user
	$line = mysqli_fetch_array($res);
	$start = $line['start'];
	$dest = $line['arrive'];
	
	//search if there is someone who still have the start or the arrive as places of his/her book
	$query1 = "SELECT * FROM member WHERE (start='" . $start . "' || arrive='" . $start . "');";	
	$query2 = "SELECT * FROM member WHERE (start='" . $dest . "' || arrive='" . $dest . "');";	
		
	try {
		mysqli_autocommit($conn, false);
		
		$res1 = mysqli_query($conn, $query1);
		$res2 = mysqli_query($conn, $query2);
		
		if(!$res1 || !$res2){
		throw new Exception($conn->error);
		}
		
	}
	catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res1);
		mysqli_free_result($res2);
		header("Location: userpage.php?book=queryfailed1");
		exit();
	}
	
	//store the number of users that still have the start or the destination in their book
	$nrows1 = mysqli_num_rows($res1);
	$nrows2 = mysqli_num_rows($res2);
	
	//set the query to do in case no one have anymore $start or $dest in their book
	if(($nrows1 == 1) && ($nrows2 == 1)){
		$query = "DELETE FROM places WHERE (place='" . $start . "' || place='" . $dest . "');";
	}
	else{
		if($nrows1 == 1){
			$query = "DELETE FROM places WHERE place='" . $start . "';";
		}
		if($nrows2 == 1){
			$query = "DELETE FROM places WHERE place='" . $dest . "';";
		}
	}
	
	//delete the book from the user
	$query1 = "UPDATE member 
			SET start = NULL, arrive = NULL, persons = 0
			WHERE name = '" . $_SESSION['user'] . "';";
	
	try {
		mysqli_autocommit($conn, false);
		$res = true;
		
		//do the query only if there is at least one place to delete
		if(($nrows1 == 1) || ($nrows2 == 1)){
			$res = mysqli_query($conn, $query);
		}
		
		$res1 =  mysqli_query($conn, $query1);
		
		if(!$res || !$res1){
		throw new Exception($conn->error);
		}
		
		mysqli_commit($conn);
	}
	catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res);
		mysqli_free_result($res1);
		header("Location: userpage.php?book=queryfailed2");
		exit();
	}
	
	mysqli_close($conn);
	mysqli_free_result($res);
	mysqli_free_result($res1);
	mysqli_free_result($res2);
	header("Location: userpage.php?book=delete_success");
	exit();
	
}
else{
	mysqli_close($conn);
	header("Location: userpage.php?book=notpost");
	exit();
}

?>
