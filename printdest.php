<?php
	
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
	
	$max_seats = 10;
	
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
		mysqli_free_result($res);
		header("Location: index.php?destination=queryfailed");
		exit();
	}
	
	//verify if there is at least two places
	$nrows = mysqli_num_rows($res);
	
	if($nrows > 1){
		$places = array();
		
		for($x=0; $x<$nrows; $x++){
			$row = mysqli_fetch_array($res);
			array_push($places, $row['place']);
		}
		
		sort($places);
		
		$p1 = $places[0];
	
		for($x=1; $x<$nrows; $x++){
			//initialize the seats
			$nseats = 0;
			
			$p2 = $places[$x];
			
			// take all the trip of tipe start=$p1 or arrive=$p2 or those of the type (start -> $p1 -> $p2 -> $arrive)
			//done to count the seats for every segment
			$query = "SELECT * FROM member WHERE (start='" . $p1 . "' || arrive='" . $p2 . "') || 
												 (start<='" . $p1 . "' && arrive>='" . $p2 . "');";
		
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
				header("Location: index.php?table=queryfailed");
				exit();
			}
			
			//take the nu,ber of the places selected
			$n = mysqli_num_rows($res);
			
			for($j=0; $j<$n; $j++){
				$line = mysqli_fetch_array($res);
				$nseats += $line['persons'];
			}
			
			if($nseats > 0){
				echo '<li class="list-group-item list-group-item-success">' . 
				($x) . ') &nbsp &nbsp' . $p1 . '&nbsp &nbsp --> &nbsp &nbsp'. $p2 . '&nbsp &nbsp' . 
				'number of seats booked: &nbsp' . $nseats  . '/' . $max_seats .'</li>';
			}
			else{
				echo '<li class="list-group-item list-group-item-success">' . 
				($x) . ') &nbsp &nbsp' . $p1 . '&nbsp &nbsp --> &nbsp &nbsp'. $p2 . '&nbsp &nbsp' . 
				'No persons on this segment </li>';
			}
			
			$p1 = $p2;
		}
	
	}
	else{
		echo '<li class="list-group-item list-group-item-success">No places available!</li>';
	}
	
	mysqli_free_result($res);
		
		
?>
		