<?php
// 50/50 lifeline script
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
isset($_SESSION['gameStarted']) or die ("Error: Game not started!<br><a href='index.php'>Return</a>");

if (strpos($_SESSION['lifelines'],'f') !== false)
{
	$llManager = new lifelinesManager();
	$incorrectAnswers = $llManager->getFiftyAnswers($_SESSION['questionNumber'], $_SESSION['packname']);
	echo $incorrectAnswers;
}
else
{
	echo gettext('Error: lifeline already used!');
}
$_SESSION['lifelines'] = str_replace('f', '', $_SESSION['lifelines']);
?>
