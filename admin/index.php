<?php
session_start();
header('Content-type: text/html; charset=utf-8');
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
{
	echo "<script>window.location.href = 'panel.php'</script>";
}
else
{
	echo "<script>window.location.href = 'login_form.php'</script>";
}
?>
