<?php

session_start();

if(isset($_POST['book'])){
	
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
	
	$max_seats = 10;
	$exist_start = false;
	$exist_dest = false;
	
	//sanitize the input string from ddangerous characters
	$start = mysqli_real_escape_string($conn, $_POST['start']);
	$start = strtoupper($start);
	$start = stripcslashes($start);
	$start = strip_tags($start);
	$start = trim($start);
	$start = preg_replace("/[^A-Z]/" , "", $start);
	
	$dest = mysqli_real_escape_string($conn, $_POST['destination']);
	$dest = strtoupper($dest);
	$dest = stripcslashes($dest);
	$dest = strip_tags($dest);
	$dest = trim($dest);
	$dest = preg_replace("/[^A-Z]/" , "", $dest);
	
	$people = mysqli_real_escape_string($conn, $_POST['people']);
	$people = stripcslashes($people);
	$people = strip_tags($people);
	$people = trim($people);
	$people = preg_replace("/[^0-9]/" , "", $people);
	
	//check if a field is empty or wrong
	if(empty($start) || empty($dest) || ($people > $max_seats) || ($people <= 0) || (strcasecmp($start, $dest) > 0) || (strcasecmp($start, $dest) == 0)){
		mysqli_close($conn);
		echo '
			<script>
			alert("Booking operation failed, field empty or wrong");
			window.location.href = "userpage.php?book=empty_or_wrng";
			</script>
		';
		exit();
	}
	else{
		
		$query = "SELECT * FROM places";
		
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
			echo '
				<script>
				alert("Booking operation failed,  query unsuccessful");
				window.location.href = "userpage.php?book=queryfailed_1";
				</script>
			';
			mysqli_free_result($res);
			exit();
		}
		
		$nrows = mysqli_num_rows($res);
		$places = array();
		
		//store all the places
		for($i=0; $i<$nrows; $i++){
			$line = mysqli_fetch_array($res);
			array_push($places, $line['place']);
			
			//checking in a case insensitive way
			if(strcasecmp($line['place'],$start) == 0){
				$exist_start = true;
			}
			if(strcasecmp($line['place'],$dest) == 0){
				$exist_dest = true;
			}		
		}
		
		sort($places);
		
		//for all the segments
		for($i=0; $i<($nrows-1); $i++){
			
			//initialize the seats
			$nseats = 0;
			
			//select all the booking that include the segment
			$query = "SELECT * FROM member WHERE (start<='" . $places[$i] . "' && arrive>='" . $places[$i+1] . "') FOR UPDATE;";
		
			try {
				mysqli_autocommit($conn, false);
				
				$res = mysqli_query($conn, $query);
				
				if(!$res){
				throw new Exception($conn->error);
				}
				
				//mysqli_commit($conn);
			}
			catch(Exception $e){
				mysqli_rollback($conn);
				mysqli_close($conn);
				echo '
					<script>
					alert("Booking operation failed,  query unsuccessful");
					window.location.href = "userpage.php?book=queryfailed_2";
					</script>
				';
				mysqli_free_result($res);
				exit();
			}
			
			//store the number of places selected
			$n = mysqli_num_rows($res);
			
			//calculate the number of seats adding all the places selected 
			for($j=0; $j<$n; $j++){
				$line = mysqli_fetch_array($res);
				$nseats += $line['persons'];
			}
			
			//verify if add the seats of the user's booking becaused include the segment
			if( (!(strcasecmp($dest, $places[$i]) < 0)) && (!(strcasecmp($start, $places[$i+1]) > 0)) ){
				$nseats += $people;
			}
			
			//check if the number of seats is not over the limit
			if($nseats > $max_seats){
				mysqli_close($conn);
				echo '
					<script>
					alert("Booking operation failed,  shuttle full");
					window.location.href = "userpage.php?book=shuttle_full";
					</script>
				';
				mysqli_free_result($res);
				exit();
			}	
		}
		
		//create the queryes to insert the new places in the database, if found any
		if($exist_start == false){
			$query_places1 = "INSERT INTO places (place) VALUES ('" . $start . "');";
		}
		if($exist_dest == false){
			$query_places2 = "INSERT INTO places (place) VALUES ('" . $dest . "');";
		}
		
		$query = "UPDATE member SET start = '" . $start . "', arrive = '" . $dest . "', persons = " . $people . " WHERE name = '" . $_SESSION['user'] . "';";
		
		try {
			mysqli_autocommit($conn, false);
			//initialize at this value if the two query are not executed
			$res2 = 1;
			$res3 = 1;
			
			$res = mysqli_query($conn, $query);
			
			if($exist_start == false){
				$res2 = mysqli_query($conn, $query_places1);
			}
			if($exist_dest == false){
				$res3 = mysqli_query($conn, $query_places2);
			}
			
			if(!$res || !$res2 || !$res3){
				throw new Exception($conn->error);
			}
			
			mysqli_commit($conn);
		}
		catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			echo '
				<script>
				alert("Booking operation failed,  query unsuccessful");
				window.location.href = "userpage.php?book=queryfailed_3";
				</script>
			';
			mysqli_free_result($res);
			mysqli_free_result($res2);
			mysqli_free_result($res3);
			exit();
		}
		
		mysqli_close($conn);
		
		echo '
			<script>
			alert("Booking operation successful");
			window.location.href = "userpage.php?book=success";
			</script>
		';
		mysqli_free_result($res);
		mysqli_free_result($res2);
		mysqli_free_result($res3);
		exit();
		
	}
}
else{
	mysqli_close($conn);
	echo '
		<script>
		alert("Book operation failed");
		window.location.href = "userpage.php?book=notpost";
		</script>
	';
	exit();
}

?>
