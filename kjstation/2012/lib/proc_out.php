<? 
	session_start();
	session_unregister($userId);
	session_unregister($userseq);
	session_unregister($userId);

	session_destroy();

	//setcookie("userseq","",time() - 3600."/");

	header("Location:../../login.php");
?>