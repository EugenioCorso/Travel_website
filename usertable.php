<?php
	
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
	$booked = 0;
	
	$query = "SELECT * FROM places";
	$query_user = "SELECT * FROM member WHERE name='" . $_SESSION['user'] . "';";
		
	try {
		mysqli_autocommit($conn, false);
		
		$res = mysqli_query($conn, $query);
		$res2 = mysqli_query($conn, $query_user);
		
		if(!$res || !$res2){
		throw new Exception($conn->error);
		}
		
		mysqli_commit($conn);
	}
	catch(Exception $e){
		mysqli_rollback($conn);
		mysqli_close($conn);
		mysqli_free_result($res);
		mysqli_free_result($res2);
		header("Location: userpage.php?destination=queryfailed");
		exit();
	}
	
	//take the start and the destination of the user
	$row = mysqli_fetch_array($res2);
	$start = $row['start'];
	$dest = $row['arrive'];
	
	//rows with all the places 
	$nrows = mysqli_num_rows($res);
	
	if($nrows > 1){
		
		//take all the users form the database
		$query = "SELECT * FROM member ";
		
		try {
			mysqli_autocommit($conn, false);
			
			$res2 = mysqli_query($conn, $query);
			
			if(!$res2){
			throw new Exception($conn->error);
			}
			
			mysqli_commit($conn);
		}
		catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res2);
			header("Location: userpage.php?table=queryfailed");
			exit();
		}
		
		$n = mysqli_num_rows($res2);
		
		for($j=0; $j<$n; $j++){
			$line = mysqli_fetch_array($res2);
			//$nseats += $line['persons'];
			
			//store all the users booked, their start and arrive and their number of seats 
			$v_users[$j] = $line['name'];
			$v_start[$j] = $line['start'];
			$v_dest[$j] = $line['arrive'];
			$v_seats[$j] = $line['persons'];
		}
		
		//initialize the array
		$places = array();
		
		//store all the places
		for($x=0; $x<$nrows; $x++){
			$row = mysqli_fetch_array($res);
			array_push($places, $row['place']);
		}
		
		sort($places);
		
		$p1 = $places[0];
		
		//for every segment
		for($x=1; $x<$nrows; $x++){
			//initialize the seats
			$nseats = 0;
			
			$p2 = $places[$x];
			
			//check all members book
			for($j=0; $j<$n; $j++){
				//if the segment is between a book then add the passengers
				if((strcasecmp($v_start[$j], $p1) <= 0) && (strcasecmp($v_dest[$j], $p2) >= 0)){
					$nseats += $v_seats[$j];
					
					//store all the users booked and their number of seats 
					$users[$v_users[$j]] = $v_seats[$j];
				}
			}	
			
			//if one of the place is the start or the arrive of the user logged highlight it
			if(strcasecmp($p1, $start) == 0){
				$booked = 1;
				echo '<li class="list-group-item list-group-item-success">' . 
					($x) . ') &nbsp &nbsp <b style="color:red">' . $p1 . '</b> &nbsp &nbsp --> &nbsp &nbsp';
			}
			else{
				echo '<li class="list-group-item list-group-item-success">' . 
					($x) . ') &nbsp &nbsp' . $p1 . '&nbsp &nbsp --> &nbsp &nbsp';
			}
			
			if(strcasecmp($p2, $dest) == 0){
				//check if there are no persons booked
				if($nseats == 0){
					echo '<b style="color:red">' . $p2 . '</b> &nbsp &nbsp' . 
						'empty &nbsp' . $nseats  . '/'. $max_seats;  //do not close the </li> tag for sub list!
				}
				else{
					echo '<b style="color:red">' . $p2 . '</b> &nbsp &nbsp' . 
						'number of seats booked: &nbsp' . $nseats  . '/'. $max_seats;  //do not close the </li> tag for sub list!
				}
			}else{
				//check if there are no persons booked
				if($nseats == 0){
					echo '' . $p2 . '&nbsp &nbsp' . 
						'empty &nbsp' . $nseats  . '/'. $max_seats;  //do not close the </li> tag for sub list!
				}
				else{
					echo '' . $p2 . '&nbsp &nbsp' . 
						'number of seats booked: &nbsp' . $nseats  . '/'. $max_seats;  //do not close the </li> tag for sub list!
				}
			}
			
			
			if(isset($users)){
				//open the sublist
				echo '<ul class="list-group">';
				
				foreach ($users as $user => $value){
					
					if($_SESSION['user'] == $user){
						echo '<li class="list-group-item list-group-item-danger">' . $user . 
						'&nbsp &nbsp' . $value . '&nbsp &nbsp persons booked';
					}
					else{
						echo '<li class="list-group-item list-group-item-success">' . $user . 
						'&nbsp &nbsp' . $value . '&nbsp &nbsp persons booked';
					}
				}
				
				echo '</ul>
					</li>'; //close the sublist and the </li> tag
			}
			else{
				echo '</li>';
			}
			
			//reset and eventually free the array $user
			unset($users);
			
			$p1 = $p2;
		}
	}
	else{
		echo '<li class="list-group-item list-group-item-success">No places available!</li>';
	}
	
	mysqli_free_result($res);
	
	if(!$booked){
		echo '
			<form id="reservation" action="booking.php" method="POST">
				<h4 style="text-align: center">Make your book</h4>
			  <div class="form-group">
				<label>Start</label>
				<input list="str" name="start" class="form-control" placeholder="Start">
					<datalist id="str">';
				  
						for($i=0; $i<$nrows; $i++){
							echo '<option value=' . $places[$i] . '>';
						}
			  
		echo '		</datalist>
			  </div>
			  
			  <div class="form-group">
				<label>Destination</label>
				<input list="dest" name="destination" class="form-control" placeholder="Destination">
					<datalist id="dest">';
					
						for($i=0; $i<$nrows; $i++){
							echo '<option value=' . $places[$i] . '>';
						}
		
		echo '
					</datalist>
			  </div>
			  
			  <div class="form-group">
				<label>Number of people</label>
				<input type="number" name="people" class="form-control" min="1" max="10">
			  </div>
			  <div class="text-center">
				<button type="submit" class="btn btn-info" name="book">Book</button>
			  </div>
			</form>
		';
	}
	else{
		echo '
			<form id="reservation" action="delete.php" method="POST">
				<h4 style="text-align: center">Delete your book</h4>
				<div class="text-center">
					<button type="submit" class="btn btn-danger" name="delete">Delete</button>
				</div>
			</form>
		';
	}
	
	
		
?>
		