<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
(isset($_SESSION['gameStarted']) && $_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
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
$queManager = new questionsManager();
$contentCount = $queManager->getContentCount($_SESSION['packname']);
($_GET['contentNumber'] <= $_SESSION['contentNumber'] && $_GET['contentNumber'] > 0) or die("Error: Incorrect content number!");
($_GET['contentNumber'] <= $contentCount) or die("<script>window.location.href = 'afterlast.php'</script>");
$row = $queManager->getContent($_SESSION['packname'], $_GET['contentNumber']);
echo "<div style='text-align: center'>";
echo "<div id='checkDiv' style='border-width: 1px 1px 0px 1px; border-radius: 10px 10px 0px 0px;'>LibreAnswer</div>";
if ($row['contentType'] == 'abcd')
{
	// IF: abcd question
	if (isset($_GET['formAnswer']) && ($_GET['formAnswer'] == '1' || $_GET['formAnswer'] == '2' || $_GET['formAnswer'] == '3' || $_GET['formAnswer'] == '4'))
	{
		// IF: valid answer
		if ($row['correctAnswer'] == $_GET['formAnswer'])
		{
			// IF: correct answer
			echo "<div id='checkDiv' style='color: limegreen; font-weight: bold;'>".gettext('Correct answer!')."</div>";
			if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
			{
				// IF: current content
				$_SESSION['contentNumber']++;
				$_SESSION['questionNumber']++;
				$_SESSION['correctUserAnswers']++;
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
				}
			}
			else
			{
				// IF: not current content
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
				}
			}
		}
		else
		{
			// IF: incorrect answer
			echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Incorrect answer!')."</div>";
			$correctAnswer = $row['correctAnswer'];
			$correctAnswerText = htmlentities($row['answer'.$correctAnswer]);
			echo "<div id='checkDiv'>".gettext("Correct answer:")." <b>".chr(96 + $correctAnswer)."</b> (".$correctAnswerText.")</div>";
			if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
			{
				if ($_SESSION['hardcoreMode'])
				{
					// IF: hardcore mode
					$_SESSION['gameStarted'] = false;
					echo "<div id='checkDiv' style='text-align: center'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
				}
				else
				{
					$_SESSION['contentNumber']++;
					$_SESSION['questionNumber']++;
					if ($_SESSION['contentNumber'] > $contentCount)
					{
						echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
					}
					else
					{
						echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
					}
				}
			}
			else
			{
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
				}
			}
		}
	}
	else
	{
		// IF: invalid answer
		echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
		$correctAnswer = $row['correctAnswer'];
		$correctAnswerText = htmlentities($row['answer'.$correctAnswer]);
		echo "<div id='checkDiv'>".gettext("Correct answer:")." <b>".chr(96 + $correctAnswer)."</b> (".$correctAnswerText.")</div>";
		if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
		{
			if ($_SESSION['hardcoreMode'])
			{
				// IF: hardcore mode
				$_SESSION['gameStarted'] = false;
				echo "<div id='checkDiv' style='text-align: center'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
			}
			else
			{
				$_SESSION['contentNumber']++;
				$_SESSION['questionNumber']++;
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
				}
			}
		}
		else
		{
			if ($_SESSION['contentNumber'] > $contentCount)
			{
				echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
			}
			else
			{
				echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
			}
		}
	}
	if ($row['questionExtra'] != '')
	{
		echo "<div id='checkDiv' style='background-color: peachpuff'>".str_replace("|n|", "<br>", htmlentities($row['questionExtra'])).'</div>';
	}
}
if ($row['contentType'] == 'tf')
{
	$isValidAnswer = false;
	$userAnswer = '';
	// IF: tf question
	if (isset($_GET['tfAnswer1']) && isset($_GET['tfAnswer2']) && isset($_GET['tfAnswer3']) && isset($_GET['tfAnswer4']))
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
		echo "<div id='checkDiv' style='background-color: khaki'>".$queManager->getContent($_SESSION['packname'], $_GET['contentNumber'])['question']."</div>";
		$correctAnswer = $queManager->getContent($_SESSION['packname'], $_GET['contentNumber'])['correctAnswer'];
		$isAllCorrect = true;
		for ($i = 0; $i<4; $i++)
		{
			echo "<div id='checkDiv'>";
			$answerText = htmlentities($queManager->getContent($_SESSION['packname'], $_GET['contentNumber'])['answer'.($i+1)]);
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
			// IF: correct answer
			echo "<div id='checkDiv' style='color: limegreen; font-weight: bold;'>".gettext('All answers are correct!')."</div>";
			if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
			{
				// IF: current content
				$_SESSION['contentNumber']++;
				$_SESSION['questionNumber']++;
				$_SESSION['correctUserAnswers']++;
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
				}
			}
			else
			{
				// IF: not current content
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
				}
			}
		}
		else
		{
			// IF: incorrect answer
			echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Incorrect answer(s)!')."</div>";
			if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
			{
				// IF: current content
				if ($_SESSION['hardcoreMode'])
				{
					// IF: hardcore mode
					$_SESSION['gameStarted'] = false;
					echo "<div id='checkDiv' style='text-align: center'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
				}
				else
				{
					$_SESSION['contentNumber']++;
					$_SESSION['questionNumber']++;
					if ($_SESSION['contentNumber'] > $contentCount)
					{
						echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
					}
					else
					{
						echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
					}
				}
			}
			else
			{
				// IF: not current content
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
				}
			}
		}
	}
	else
	{
		// IF: invalid answer
		echo "<div id='checkDiv' style='color: crimson; font-weight: bold; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
		echo "<div id='checkDiv' style='background-color: goldenrod; font-weight: bold;'>".gettext("Correct answers:")."</div>";
		echo "<div id='checkDiv' style='background-color: khaki'>".$queManager->getContent($_SESSION['packname'], $_GET['contentNumber'])['question']."</div>";
		$correctAnswer = $queManager->getContent($_SESSION['packname'], $_GET['contentNumber'])['correctAnswer'];
		for ($i = 0; $i<4; $i++)
		{
			echo "<div id='checkDiv'>";
			$answerText = htmlentities($queManager->getContent($_SESSION['packname'], $_GET['contentNumber'])['answer'.($i+1)]);
			echo "<div style='border: 1px solid black; padding: 1px; background-color: antiquewhite; width: 50vw; display: inline-block; margin: auto; text-align: center;'>";
			echo $answerText." - ";
			echo "<b>";
			if ($correctAnswer[$i] == 't')
			{
				echo gettext("true");
			}
			else
			{
				echo gettext("false");
			}
			echo "</b>";
			echo "</div>";
			echo "</div>";
		}
		if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
		{
			// IF: current content
			if ($_SESSION['hardcoreMode'])
			{
				// IF: hardcore mode
				$_SESSION['gameStarted'] = false;
				echo "<div id='checkDiv' style='text-align: center'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
			}
			else
			{
				$_SESSION['contentNumber']++;
				$_SESSION['questionNumber']++;
				if ($_SESSION['contentNumber'] > $contentCount)
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
				}
				else
				{
					echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
				}
			}
		}
		else
		{
			// IF: not current content
			if ($_SESSION['contentNumber'] > $contentCount)
			{
				echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
			}
			else
			{
				echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
			}
		}
	}
	if ($row['questionExtra'] != '')
	{
		echo "<div id='checkDiv' style='background-color: peachpuff'>".str_replace("|n|", "<br>", htmlentities($row['questionExtra'])).'</div>';
	}
}
if ($row['contentType'] == 'info')
{
	// IF: info-card
	echo "<div id='checkDiv' style='color: #855628; font-weight: bold; padding-top: 10px; font-size: 25px;'>".$row['title']."</div>";
	echo "<div id='checkDiv' style='color: black; padding-bottom: 10px;'>".$row['description']."</div>";
	if ($_GET['contentNumber'] == $_SESSION['contentNumber'])
	{
		// IF: current content
		$_SESSION['contentNumber']++;
		if ($_SESSION['contentNumber'] > $contentCount)
		{
			echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
		}
		else
		{
			echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('Next')."</a></div>";
		}
	}
	else
	{
		// IF: not current content
		if ($_SESSION['contentNumber'] > $contentCount)
		{
			echo "<div id='checkDiv' style='font-weight: bold'><a href='afterlast.php' style='color: black'>".gettext('End game')."</a></div>";
		}
		else
		{
			echo "<div id='checkDiv' style='font-weight: bold'><a href='question.php' style='color: black'>".gettext('OK')."</a></div>";
		}
	}
}
echo "<div id='checkDiv' style='border-width: 0px 1px 1px 1px; border-radius: 0px 0px 10px 10px; height: 20px;'></div>";
echo "</div>";
?>
</body>
</html>
