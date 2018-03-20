<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
isset($_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
$correctAnswer = '';
$queManager = new questionsManager();
$queCount = $queManager->getQuestionsCount($_SESSION['packname']);
if ($_SESSION['packType'] == 'test')
{
	$formAnswer = '';
	if (isset($_GET['formAnswer']))
	{
		$formAnswer = $_GET['formAnswer'];
	}
	if ($formAnswer == '1' || $formAnswer == '2' || $formAnswer == '3' || $formAnswer == '4')
	{
		$isValidAnswer = true;
		$_SESSION['formAnswer'] = $formAnswer;
		$_SESSION['userAnswers'][$_SESSION['questionNumber']] = $_SESSION['formAnswer'];
	}
	if ($formAnswer == '')
	{
		$isValidAnswer = true;
		$_SESSION['formAnswer'] = '0';
		$_SESSION['userAnswers'][$_SESSION['questionNumber']] = '0';
	}
	if ($_SESSION['questionNumber'] == 1)
	{
		header('Location: question.php');
	}
	else
	{
		$_SESSION['questionNumber'] -= 1;
		header('Location: question.php');
	}
}
else if ($_SESSION['packType'] == 'standard')
{
	echo 'Not available in this mode';
}
else if ($_SESSION['packType'] == 'quiz')
{
	echo 'Not available in this mode';
}
?>
