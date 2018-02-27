<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
isset($_SESSION['gameStarted']) or die ("Error: Game not started!<br><a href='index.php'>Return</a>");
?>
<html>
<head>
<title>LibreAnswer</title>
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
if ($_SESSION['packType'] == 'standard')
{
	$correctAnswer = '';
	$queManager = new questionsManager();
	$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
	$isValidAnswer = false;
	$formAnswer = '0';
	if (isset($_GET['formAnswer']))
	{
		$formAnswer = $_GET['formAnswer'];
	}
	if ($formAnswer == '1' || $formAnswer == '2' || $formAnswer == '3' || $formAnswer == '4')
	{
		$isValidAnswer = true;
		$_SESSION['formAnswer'] = $formAnswer;
	}
	if ($isValidAnswer)
	{
		if ($_SESSION['formAnswer'] == $correctAnswer)
		{
			echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('Question')." ".$_SESSION['questionNumber']."</div>";
			echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px;'>".gettext('Correct answer:')." ".chr(96 + $correctAnswer)."</div>";
			echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px; color: green;'>".gettext('Correct answer!');
		}
		else
		{
			$correctAnswerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.$correctAnswer]);
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('Question')." ".$_SESSION['questionNumber']."</div>";
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>".gettext('Correct answer:')." ".chr(96 + $correctAnswer)." (".$correctAnswerText.")</div>";
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px; color: black;'>".gettext('Incorrect answer!');
		}
	}
	else
	{
		echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px; color: black;'>".gettext('Invalid/no answer passed!')."</div>";
		$correctAnswerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.$correctAnswer]);
		echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>".gettext('Correct answer:')." ".chr(96 + $correctAnswer)." (".$correctAnswerText.")</div>";
	}
	echo '</div>';
	if ($_SESSION['formAnswer'] == $correctAnswer && $isValidAnswer)
	{
		echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px;'>";
		echo "<a href='next.php' style='color: black; text-decoration: none; background-color: palegreen; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
	}
	else
	{
		echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>";
		echo "<a href='endgame.php' style='color: black; text-decoration: none; background-color: #f4a4a4; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('End game')."</a>";
	}
	echo '</div>';
}
else if ($_SESSION['packType'] == 'test')
{
	echo 'Not available in this mode';
}
else if ($_SESSION['packType'] == 'quiz')
{
	$correctAnswer = '';
	$queManager = new questionsManager();
	$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
	$isValidAnswer = false;
	$formAnswer = '0';
	if (isset($_GET['formAnswer']))
	{
		$formAnswer = $_GET['formAnswer'];
	}
	if ($formAnswer == '1' || $formAnswer == '2' || $formAnswer == '3' || $formAnswer == '4')
	{
		$isValidAnswer = true;
		$_SESSION['formAnswer'] = $formAnswer;
	}
	if ($isValidAnswer)
	{
		if ($_SESSION['formAnswer'] == $correctAnswer)
		{
			echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('Question')." ".$_SESSION['questionNumber']."</div>";
			echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px; color: green;'>".gettext('Correct answer!');
		}
		else
		{
			$correctAnswerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.$correctAnswer]);
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('Question')." ".$_SESSION['questionNumber']."</div>";
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>".gettext('Correct answer:')." ".chr(96 + $correctAnswer)." (".$correctAnswerText.")</div>";
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px; color: black;'>".gettext('Incorrect answer!');
		}
	}
	else
	{
		$correctAnswerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.$correctAnswer]);
		echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px; color: black;'>".gettext('Invalid/no answer passed!')."</div>";
		echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>".gettext('Correct answer:')." ".chr(96 + $correctAnswer)." (".$correctAnswerText.")</div>";
	}
	echo '</div>';
	if ($_SESSION['formAnswer'] == $correctAnswer && $isValidAnswer)
	{
		echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px;'>";
		echo "<a href='next.php' style='color: black; text-decoration: none; background-color: gainsboro; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
	}
	else
	{
		echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>";
		echo "<a href='next.php' style='color: black; text-decoration: none; background-color: gainsboro; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
	}
	echo '</div>';
}
?>
</body>
</html>
