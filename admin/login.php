<?php
session_start();
header('Content-type: text/html; charset=utf-8');
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
{
	echo gettext('Already logged in!').'<br>';
	echo "<a href='panel.php'>Panel</a>";
}
else
{
	$dbConnector = new databaseConnector();
	if ($dbConnector->isValidAdminPassword($_POST['password']))
	{
		$_SESSION['loggedIn'] = true;
		echo gettext('Logged in, redirecting...');
		echo "<script>window.location.href = 'panel.php'</script>";
	}
	else
	{
		echo gettext('Wrong password!').'<br>';
		echo "<a href='login_form.php'>".gettext('Login form').'</a>';
	}
}
?>
