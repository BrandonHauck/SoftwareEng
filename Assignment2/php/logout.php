<?php 
	// logout.php
	session_start(); // Required for every page where you call or declare a session
	session_destroy(); // Destroys all data registered to a session
	header('Location: ../login.html'); // Redirect to login page
	exit();
?>


