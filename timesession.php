<?php

	if(isset($_SESSION['time'])){
		if(time() - $_SESSION['time'] > 120){
			session_start();
			session_unset();
			session_destroy();
			header("Location: index.php?logout=success");
			exit();
		}
		else{
			$_SESSION['time'] = time();
		}
	}
	
?>