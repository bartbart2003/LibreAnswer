<?php
// Hint lifeline script
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
($_SESSION['gameStarted'] == true) or die("Error: Game not started!<br><a href='index.php'>Return</a>");

if (strpos($_SESSION['lifelines'],'h') !== false)
{
	$llManager = new lifelinesManager();
	echo $llManager->getHintText($_SESSION['questionNumber'], $_SESSION['packname']);
}
else
{
	echo gettext('Error: lifeline already used!');
}
$_SESSION['lifelines'] = str_replace('h', '', $_SESSION['lifelines']);
?>
