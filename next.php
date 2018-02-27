<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
isset($_SESSION['gameStarted']) or die ("Error: Game not started!<br><a href='index.php'>Return</a>");
$correctAnswer = '';
$queManager = new questionsManager();
$queCount = $queManager->getQuestionsCount($_SESSION['packname']);
$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
$isValidAnswer = false;
if ($_SESSION['formAnswer'] == '1' || $_SESSION['formAnswer'] == '2' || $_SESSION['formAnswer'] == '3' || $_SESSION['formAnswer'] == '4')
{
	$isValidAnswer = true;
}
if ($_SESSION['packType'] == 'standard')
{
	if ($isValidAnswer)
	{
		// IF: Valid answer
		if ($_SESSION['formAnswer'] == $correctAnswer)
		{
			// IF: Correct answer
			if ($_SESSION['questionNumber'] >= $queCount)
			{
				// IF: Last question
				echo "<script>window.location.href = 'afterlast.php'</script>";
			}
			else
			{
				// IF: Not last question
				$_SESSION['questionNumber']++;
				echo "<script>window.location.href = 'question.php'</script>";
			}
		}
		else
		{
			// IF: Incorrect answer
			echo "<div style='color: black; text-align: center;'>".gettext('Incorrect answer!')."</div>";
			echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
		}
	}
	else
	{
		// IF: Invalid answer
		echo "<div style='color: black; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
		echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
	}
}
else if ($_SESSION['packType'] == 'test')
{
	$formAnswer = '';
	if (isset($_GET['formAnswer']))
	{
		$formAnswer = $_GET['formAnswer'];
	}
	if ($formAnswer == '')
	{
		$formAnswer = '0';
	}
	if ($formAnswer == '0' || $formAnswer == '1' || $formAnswer == '2' || $formAnswer == '3' || $formAnswer == '4')
	{
		$isValidAnswer = true;
		$_SESSION['formAnswer'] = $formAnswer;
		// Set answer in userAnswers session variable
		$_SESSION['userAnswers'][$_SESSION['questionNumber']] = $_SESSION['formAnswer'];
	}
	if ($_SESSION['questionNumber'] >= $queCount)
	{
		// IF: Last question
		echo "<script>window.location.href = 'afterlast.php'</script>";
	}
	else
	{
		// IF: Not last question
		$_SESSION['questionNumber'] += 1;
		echo "<script>window.location.href = 'question.php'</script>";
	}
}
else if ($_SESSION['packType'] == 'quiz')
{
	if ($isValidAnswer && $_SESSION['formAnswer'] == $correctAnswer)
	{
		// IF: Correct answer
		$_SESSION['correctUserAnswers']++;
	}
	if ($_SESSION['questionNumber'] >= $queCount)
	{
		// IF: Last question
		echo "<script>window.location.href = 'afterlast.php'</script>";
	}
	else
	{
		// IF: Not last question
		$_SESSION['questionNumber']++;
		echo "<script>window.location.href = 'question.php'</script>";
	}
}
?>
