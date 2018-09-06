<?php
// Hint lifeline script
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';

// Check if valid question
$queManager = new questionsManager();
($queManager->getContentCount($_SESSION['packname']) >= $_SESSION['contentNumber']) or die("Invalid question number!");
$row = $queManager->getContent($_SESSION['packname'], $_SESSION['contentNumber']);
($row['contentType'] == 'abcd' || $row['contentType'] == 'tf') or die("Error: Not a question!");

(isset($_SESSION['gameStarted']) && $_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");

if (strpos($_SESSION['lifelines'],'h') !== false)
{
	$llManager = new lifelinesManager();
	echo $llManager->getHintText($_SESSION['contentNumber'], $_SESSION['packname']);
}
else
{
	echo gettext('Error: lifeline already used!');
}
$_SESSION['lifelines'] = str_replace('h', '', $_SESSION['lifelines']);
?>
