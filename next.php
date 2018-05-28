<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
($_SESSION['gameStarted'] == true) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
?>
<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer</title>
<link rel="shortcut icon" href="favicon.ico" >
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<?php include 'stats.php'; ?>
</head>
<body>
<?php
$correctAnswer = '';
$queManager = new questionsManager();
$queCount = $queManager->getQuestionsCount($_SESSION['packname']);
$isValidAnswer = false;
$userAnswer = '';
echo "<div style='text-align: center'>";
echo "<div id='checkDiv' style='border-width: 1px 1px 0px 1px; border-radius: 10px 10px 0px 0px;'>LibreAnswer</div>";
if ($_GET['questionNumber'] == $_SESSION['questionNumber'])
{
	// Current question number
	$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
	$questionType = $queManager->getQuestionType($_SESSION['questionNumber'], $_SESSION['packname']);
	$checkedQuestionNumber = $_SESSION['questionNumber'];
	echo "<div id='checkDiv' style='font-weight: bold'>".gettext('Question')." ".$_SESSION['questionNumber']."</div>";
	// ABCD question type
	if ($questionType == 'abcd')
	{
		if (isset($_GET['formAnswer']))
		{
			$userAnswer = $_GET['formAnswer'];
		}
		if ($userAnswer == '1' || $userAnswer == '2' || $userAnswer == '3' || $userAnswer == '4')
		{
			// IF: Valid answer
			if ($userAnswer == $correctAnswer)
			{
				// IF: Correct answer
				if ($_SESSION['quizMode'])
				{
					$_SESSION['correctUserAnswers']++;
				}
				echo "<div id='checkDiv' style='color: limegreen; font-weight: bold;'>".gettext('Correct answer!')."</div>";
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					$_SESSION['questionNumber']++;
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next question')."</a></div>";
				}
			}
			else
			{
				// IF: Incorrect answer
				echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Incorrect answer!')."</div>";
				$correctAnswerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.$correctAnswer]);
				echo "<div id='checkDiv'>".gettext("Correct answer:")." <b>".chr(96 + $correctAnswer)."</b> (".$correctAnswerText.")</div>";
				if ($_SESSION['quizMode'])
				{
					// IF: Quiz mode
					if ($_SESSION['questionNumber'] >= $queCount)
					{
						// IF: Last question
						echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
					}
					else
					{
						// IF: Not last question
						$_SESSION['questionNumber']++;
						echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next question')."</a></div>";
					}
				}
				else
				{
					// IF: Not quiz mode
					$_SESSION['gameStarted'] = false;
					echo "<div id='checkDiv' style='text-align: center'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
				}
			}
		}
		else
		{
			echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
			// IF: Invalid answer
			if ($_SESSION['quizMode'])
			{
				// IF: Quiz mode
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					// IF: Last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					$_SESSION['questionNumber']++;
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next question')."</a></div>";
				}
			}
			else
			{
				// IF: Not quiz mode
				$_SESSION['gameStarted'] = false;
				echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
			}
		}
	}
	// True/false question type
	else if ($questionType == 'tf')
	{
		if (isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer1']))
		{
			$userAnswer = $_GET['tfAnswer1'].$_GET['tfAnswer2'].$_GET['tfAnswer3'].$_GET['tfAnswer4'];
		}
		// Check if answer is valid
		if (strlen($userAnswer) == '4')
		{
			$isValidAnswer = true;
			for ($i = 0; $i<4; $i++)
			{
				if ($userAnswer[$i] != 't' && $userAnswer[$i] != 'f')
				{
					$isValidAnswer = false;
				}
			}
		}
		if ($isValidAnswer)
		{
			echo "<div id='checkDiv' style='background-color: goldenrod; font-weight: bold;'>".gettext("Correct answers:")."</div>";
			echo "<div id='checkDiv' style='background-color: khaki'>".$queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['question']."</div>";
			$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
			$isAllCorrect = true;
			for ($i = 0; $i<4; $i++)
			{
				echo "<div id='checkDiv'>";
				$answerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber'])['answer'.($i+1)]);
				echo "<div style='border: 1px solid black; padding: 1px; background-color: antiquewhite; width: 50vw; display: inline-block; margin: auto; text-align: center;'>";
				echo $answerText." - ";
				if ($correctAnswer[$i] != $userAnswer[$i])
				{
					echo "<b>";
					$isAllCorrect = false;
				}
				if ($correctAnswer[$i] == 't')
				{
					echo gettext("true");
				}
				else
				{
					echo gettext("false");
				}
				if ($correctAnswer[$i] != $userAnswer[$i])
				{
					echo "</b>";
				}
				echo "</div>";
				echo "</div>";
			}
			if ($isAllCorrect)
			{
				if ($_SESSION['quizMode'])
				{
					$_SESSION['correctUserAnswers']++;
				}
				echo "<div id='checkDiv' style='color: limegreen; font-weight: bold;'>".gettext('Correct answer!')."</div>";
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					// IF: Last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					$_SESSION['questionNumber']++;
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next question')."</a></div>";
				}
			}
			else
			{
				echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Incorrect answer!')."</div>";
				// IF: Incorrect answer
				if ($_SESSION['quizMode'])
				{
					// IF: Quiz mode
					if ($_SESSION['questionNumber'] >= $queCount)
					{
						// IF: Last question
						echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
					}
					else
					{
						// IF: Not last question
						$_SESSION['questionNumber']++;
						echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next question')."</a></div>";
					}
				}
				else
				{
					// IF: Not quiz mode
					$_SESSION['gameStarted'] = false;
					echo "<div id='checkDiv' style='font-weight: bold'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
				}
			}
		}
		else
		{
			echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
			// IF: Invalid answer
			if ($_SESSION['quizMode'])
			{
				// IF: Quiz mode
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					// IF: Last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					$_SESSION['questionNumber']++;
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next question')."</a></div>";
				}
			}
			else
			{
				// IF: Not quiz mode
				$_SESSION['gameStarted'] = false;
				echo "<div id='checkDiv' style='font-weight: bold'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
			}
		}
	}
	$extraInfo = $queManager->getExtraInfo($checkedQuestionNumber, $_SESSION['packname']);
	if ($extraInfo != '')
	{
		echo "<div id='checkDiv' style='background-color: peachpuff'>".str_replace("|n|", "<br>", htmlentities($extraInfo)).'</div>';
	}
}
else if ($_GET['questionNumber'] < $_SESSION['questionNumber'])
{
	// Previous question number - only view
	$getQuestionNumber = $_GET['questionNumber'];
	$correctAnswer = $queManager->getValidAnswer($_GET['questionNumber'], $_SESSION['packname']);
	$questionType = $queManager->getQuestionType($_GET['questionNumber'], $_SESSION['packname']);
	echo "<div id='checkDiv' style='font-weight: bold'>".gettext('Question')." ".$getQuestionNumber."</div>";
	// ABCD question type
	if ($questionType == 'abcd')
	{
		if (isset($_GET['formAnswer']))
		{
			$userAnswer = $_GET['formAnswer'];
		}
		if ($userAnswer == '1' || $userAnswer == '2' || $userAnswer == '3' || $userAnswer == '4')
		{
			// IF: Valid answer
			if ($userAnswer == $correctAnswer)
			{
				// IF: Correct answer
				echo "<div id='checkDiv' style='color: limegreen; font-weight: bold;'>".gettext('Correct answer!')."</div>";
				if ($getQuestionNumber >= $queCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
				}
			}
			else
			{
				// IF: Incorrect answer
				echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Incorrect answer!')."</div>";
				$correctAnswerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $getQuestionNumber)['answer'.$correctAnswer]);
				echo "<div id='checkDiv'>".gettext("Correct answer:")." <b>".chr(96 + $correctAnswer)."</b> (".$correctAnswerText.")</div>";
				if ($_SESSION['quizMode'])
				{
					// IF: Quiz mode
					if ($_SESSION['questionNumber'] >= $queCount)
					{
						// IF: Last question
						echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
					}
					else
					{
						// IF: Not last question
						echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
					}
				}
				else
				{
					// IF: Not quiz mode
					echo "<div id='checkDiv' style='font-weight: bold'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
				}
			}
		}
		else
		{
			echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
			// IF: Invalid answer
			if ($_SESSION['quizMode'])
			{
				// IF: Quiz mode
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					// IF: Last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
				}
			}
			else
			{
				// IF: Not quiz mode
				echo "<div id='checkDiv' style='font-weight: bold'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
			}
		}
	}
	// True/false question type
	else if ($questionType == 'tf')
	{
		$userAnswer = '';
		if (isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer1']))
		{
			$userAnswer = $_GET['tfAnswer1'].$_GET['tfAnswer2'].$_GET['tfAnswer3'].$_GET['tfAnswer4'];
		}
		// Check if answer is valid
		if (strlen($userAnswer) == '4')
		{
			$isValidAnswer = true;
			for ($i = 0; $i<4; $i++)
			{
				if ($userAnswer[$i] != 't' && $userAnswer[$i] != 'f')
				{
					$isValidAnswer = false;
				}
			}
		}
		if ($isValidAnswer)
		{
			echo "<div id='checkDiv' style='background-color: goldenrod; font-weight: bold;'>".gettext("Correct answers:")."</div>";
			echo "<div id='checkDiv' style='background-color: khaki'>".$queManager->getQuestion($_SESSION['packname'], $getQuestionNumber)['question']."</div>";
			$correctAnswer = $queManager->getValidAnswer($getQuestionNumber, $_SESSION['packname']);
			$isAllCorrect = true;
			for ($i = 0; $i<4; $i++)
			{
				echo "<div id='checkDiv'>";
				$answerText = htmlentities($queManager->getQuestion($_SESSION['packname'], $getQuestionNumber)['answer'.($i+1)]);
				echo "<div style='border: 1px solid black; padding: 1px; background-color: antiquewhite; width: 50vw; display: inline-block; margin: auto; text-align: center;'>";
				echo $answerText." - ";
				if ($correctAnswer[$i] != $userAnswer[$i])
				{
					echo "<b>";
					$isAllCorrect = false;
				}
				if ($correctAnswer[$i] == 't')
				{
					echo gettext("true");
				}
				else
				{
					echo gettext("false");
				}
				if ($correctAnswer[$i] != $userAnswer[$i])
				{
					echo "</b>";
				}
				echo "</div>";
				echo "</div>";
			}
			if ($isAllCorrect)
			{
				echo "<div id='checkDiv' style='color: limegreen; font-weight: bold;'>".gettext('Correct answer!')."</div>";
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					// IF: Last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
				}
			}
			else
			{
				echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Incorrect answer!')."</div>";
				// IF: Incorrect answer
				if ($_SESSION['quizMode'])
				{
					// IF: Quiz mode
					if ($_SESSION['questionNumber'] >= $queCount)
					{
						// IF: Last question
						echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
					}
					else
					{
						// IF: Not last question
						echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
					}
				}
				else
				{
					// IF: Not quiz mode
					echo "<div id='checkDiv' style='font-weight: bold'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
				}
			}
		}
		else
		{
			echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
			// IF: Invalid answer
			if ($_SESSION['quizMode'])
			{
				if ($_SESSION['questionNumber'] >= $queCount)
				{
					// IF: Last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					// IF: Not last question
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
				}
			}
			else
			{
				echo "<div id='checkDiv' style='font-weight: bold'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
			}
		}
	}
	$extraInfo = $queManager->getExtraInfo($getQuestionNumber, $_SESSION['packname']);
	if ($extraInfo != '')
	{
		echo "<div id='checkDiv' style='background-color: peachpuff'>".str_replace("|n|", "<br>", htmlentities($extraInfo)).'</div>';
	}
}
else
{
	// Incorrect question number
	echo "<div id='checkDiv' style='font-weight: bold; color: red;'>".gettext("Incorrect question number!")."</div>";
	echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Go to question')."</a></div>";
}
echo "<div id='checkDiv' style='border-width: 0px 1px 1px 1px; border-radius: 0px 0px 10px 10px; height: 20px;'></div>";
echo "</div>";
?>
</body>
</html>
