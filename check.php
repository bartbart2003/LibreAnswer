<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
isset($_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
?>
<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer</title>
<link rel="shortcut icon" href="favicon.ico" >
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include 'stats.php'; ?>
</head>
<body>
<?php
if ($_SESSION['packType'] == 'standard' || $_SESSION['packType'] == 'quiz')
{
	$queManager = new questionsManager();
	$questionType = $queManager->getQuestionType($_SESSION['questionNumber'], $_SESSION['packname']);
	$extraInfo = $queManager->getExtraInfo($_SESSION['questionNumber'], $_SESSION['packname']);
	if ($questionType == 'abcd')
	{
		$correctAnswer = '';
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
		if ($extraInfo != '')
		{
			echo "<div style='background-color: peachpuff; text-align: center; font-size: 20px; color: black;'>".str_replace("|n|", "<br>", htmlentities($extraInfo)).'</div>';
		}
		if ($_SESSION['formAnswer'] == $correctAnswer && $isValidAnswer)
		{
			echo "<div style='background-color: lightgreen; text-align: center; font-size: 20px;'>";
			echo "<a href='next.php' style='color: black; text-decoration: none; background-color: palegreen; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
		}
		else
		{
			echo "<div style='background-color: lightcoral; text-align: center; font-size: 20px;'>";
			if ($_SESSION['packType'] == 'quiz')
			{
				echo "<a href='next.php' style='color: black; text-decoration: none; background-color: gainsboro; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
			}
			else
			{
				echo "<a href='endgame.php' style='color: black; text-decoration: none; background-color: #f4a4a4; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('End game')."</a>";
			}
		}
		echo '</div>';
	}
	else if ($questionType == 'tf')
	{
		$isValidAnswer = false;
		$isValidTfAnswer1 = false;
		$isValidTfAnswer2 = false;
		$isValidTfAnswer3 = false;
		$isValidTfAnswer4 = false;
		if (isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer2']) && isset($_GET['tfAnswer3']) && isset($_GET['tfAnswer4']))
		{
			if ($_GET['tfAnswer1'] == 't' || $_GET['tfAnswer1'] == 'f')
			{
				$isValidTfAnswer1 = true;
			}
			if ($_GET['tfAnswer2'] == 't' || $_GET['tfAnswer2'] == 'f')
			{
				$isValidTfAnswer2 = true;
			}
			if ($_GET['tfAnswer3'] == 't' || $_GET['tfAnswer3'] == 'f')
			{
				$isValidTfAnswer3 = true;
			}
			if ($_GET['tfAnswer4'] == 't' || $_GET['tfAnswer4'] == 'f')
			{
				$isValidTfAnswer4 = true;
			}
		}
		if ($isValidTfAnswer1 && $isValidTfAnswer2 && $isValidTfAnswer3 && $isValidTfAnswer4)
		{
			$isValidAnswer = true;
		}
		if ($isValidAnswer)
		{
			echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px; font-weight: bold;'>".gettext("Question")." ".$_SESSION['questionNumber']."</div>";
			echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px; font-weight: bold;'>".gettext("Correct answers/your answers:")."</div>";
			echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px;'>".$queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['question']."</div>";
			$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
			$_SESSION['formAnswer'] = $_GET['tfAnswer1'].$_GET['tfAnswer2'].$_GET['tfAnswer3'].$_GET['tfAnswer4'];
			$isAllCorrect = true;
			for ($i = 1; $i<5; $i++)
			{
				$answerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.$i]);
				echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px; color: black;'><span style='background-color: ";
				if ($correctAnswer[$i-1] == $_SESSION['formAnswer'][$i-1])
				{
					echo "palegreen";
				}
				else
				{
					echo "lightcoral";
					$isAllCorrect = false;
				}
				echo "; border-radius: 10px; border: 1px solid black; padding-left: 10px; padding-right: 10px;'>".$i.". ".$answerText.": ";
				if ($correctAnswer[$i-1] == 't')
				{
					echo gettext("true");
				}
				else
				{
					echo gettext("false");
				}
				echo " / ";
				if ($_SESSION['formAnswer'][$i-1] == 't')
				{
					echo gettext("true");
				}
				else
				{
					echo gettext("false");
				}
				echo "</span>";
				echo "</div>";
			}
			if ($extraInfo != '')
			{
				echo "<div style='background-color: peachpuff; text-align: center; font-size: 20px; color: black;'>".str_replace("|n|", "<br>", htmlentities($extraInfo)).'</div>';
			}
			if ($isAllCorrect)
			{
				echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px;'>";
				echo "<a href='next.php' style='color: black; text-decoration: none; background-color: palegreen; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
			}
			else
			{
				echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px;'>";
				if ($_SESSION['packType'] == 'quiz')
				{
					echo "<a href='next.php' style='color: black; text-decoration: none; background-color: gainsboro; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a>";
				}
				else
				{
					echo "<a href='endgame.php' style='color: black; text-decoration: none; background-color: #f4a4a4; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('End game')."</a>";
				}
			}
		}
		else
		{
			// IF: Invalid answer
			echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px; color: black;'>".gettext('Invalid/no answer passed!')."</div>";
			if ($extraInfo != '')
			{
				echo "<div style='background-color: peachpuff; text-align: center; font-size: 20px; color: black;'>".str_replace("|n|", "<br>", htmlentities($extraInfo)).'</div>';
			}
			if ($_SESSION['packType'] == 'standard')
			{
				echo "<a href='endgame.php' style='color: black; text-decoration: none; background-color: #f4a4a4; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('End game')."</a>";
			}
			else if ($_SESSION['packType'] == 'quiz')
			{
				echo "<div style='background-color: gainsboro; text-align: center; font-size: 20px;'><a href='next.php' style='color: black; text-decoration: none; background-color: gainsboro; border: 1px solid black; border-radius: 10px; padding-left: 10px; padding-right: 10px;'>".gettext('Next question')."</a></div>";
			}
		}
	}
}
else if ($_SESSION['packType'] == 'test')
{
	echo 'Not available in this mode';
}
?>
</body>
</html>
