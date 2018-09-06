<?php
// 50/50 lifeline script
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';

(isset($_SESSION['gameStarted']) && $_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");

// Check if valid question
$queManager = new questionsManager();
($queManager->getContentCount($_SESSION['packname']) >= $_SESSION['contentNumber']) or die("Invalid question number!");
$row = $queManager->getContent($_SESSION['packname'], $_SESSION['contentNumber']);
($row['contentType'] == 'abcd' || $row['contentType'] == 'tf') or die("Error: Not a question!");

($row['contentType'] != 'tf') or die("Error: Not available in this question type!");

if (strpos($_SESSION['lifelines'],'f') !== false)
{
	$llManager = new lifelinesManager();
	$incorrectAnswers = $llManager->getFiftyAnswers($_SESSION['contentNumber'], $_SESSION['packname']);
	echo $incorrectAnswers;
}
else
{
	echo gettext('Error: lifeline already used!');
}
$_SESSION['lifelines'] = str_replace('f', '', $_SESSION['lifelines']);
?>
