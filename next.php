<!DOCTYPE html>
<?php
session_start();
// Main
require_once 'private/main.php';
// Translations
require_once 'lang.php';
isset($_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
?>
<html>
<head>
<title>LibreAnswer</title>
<?php include 'stats.php'; ?>
</head>
<body>
<?php
$correctAnswer = '';
$queManager = new questionsManager();
$queCount = $queManager->getQuestionsCount($_SESSION['packname']);
$correctAnswer = $queManager->getValidAnswer($_SESSION['questionNumber'], $_SESSION['packname']);
if ($_SESSION['packType'] == 'standard' || $_SESSION['packType'] == 'quiz')
{
	$questionType = $queManager->getQuestionType($_SESSION['questionNumber'], $_SESSION['packname']);
	if ($questionType == 'abcd')
	{
		$isValidAnswer = false;
		if ($_SESSION['formAnswer'] == '1' || $_SESSION['formAnswer'] == '2' || $_SESSION['formAnswer'] == '3' || $_SESSION['formAnswer'] == '4')
		{
			$isValidAnswer = true;
		}
		if ($isValidAnswer)
		{
			// IF: Valid answer
			if ($_SESSION['formAnswer'] == $correctAnswer)
			{
				// IF: Correct answer
				if ($_SESSION['packType'] == 'quiz')
				{
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
			else
			{
				// IF: Incorrect answer
				if ($_SESSION['packType'] == 'standard')
				{
					echo "<div style='color: black; text-align: center;'>".gettext('Incorrect answer!')."</div>";
					echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
				}
				else if ($_SESSION['packType'] == 'quiz')
				{
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
			}
		}
		else
		{
			// IF: Invalid answer
			if ($_SESSION['packType'] == 'standard')
			{
				echo "<div style='color: black; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
				echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
			}
			else if ($_SESSION['packType'] == 'quiz')
			{
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
		}
	}
	else if ($questionType == 'tf')
	{
		$isValidAnswer = false;
		$isValidTfAnswer1 = false;
		$isValidTfAnswer2 = false;
		$isValidTfAnswer3 = false;
		$isValidTfAnswer4 = false;
		if (strlen($_SESSION['formAnswer']) == '4')
		{
			if ($_SESSION['formAnswer'][0] == 't' || $_SESSION['formAnswer'][0] == 'f')
			{
				$isValidTfAnswer1 = true;
			}
			if ($_SESSION['formAnswer'][1] == 't' || $_SESSION['formAnswer'][1] == 'f')
			{
				$isValidTfAnswer2 = true;
			}
			if ($_SESSION['formAnswer'][2] == 't' || $_SESSION['formAnswer'][2] == 'f')
			{
				$isValidTfAnswer3 = true;
			}
			if ($_SESSION['formAnswer'][3] == 't' || $_SESSION['formAnswer'][3] == 'f')
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
			$isAllCorrect = true;
			for ($i = 1; $i<5; $i++)
			{
				if ($correctAnswer[$i-1] != $_SESSION['formAnswer'][$i-1])
				{
					$isAllCorrect = false;
				}
			}
			if ($isAllCorrect)
			{
				$_SESSION['correctUserAnswers']++;
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
				if ($_SESSION['packType'] == 'standard')
				{
					echo "<div style='color: black; text-align: center;'>".gettext('Incorrect answer!')."</div>";
					echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
				}
				else if ($_SESSION['packType'] == 'quiz')
				{
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
			}
		}
		else
		{
			// IF: Invalid answer
			if ($_SESSION['packType'] == 'standard')
			{
				echo "<div style='color: black; text-align: center;'>".gettext('Invalid/no answer passed!')."</div>";
				echo "<div style='text-align: center;'><a href='endgame.php' style='color: black;'>".gettext('End game')."</a></div>";
			}
			else if ($_SESSION['packType'] == 'quiz')
			{
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
		}
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
?>
</body>
</html>
