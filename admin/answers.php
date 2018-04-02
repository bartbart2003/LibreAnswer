<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer Admin</title>
<link rel='stylesheet' type='text/css' href='css/style.css'><meta name='viewport' content='width=device-width, initial-scale=1'>
<?php include 'stats.php'; ?>
</head>
<body>
<?php
session_start();
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
{
	echo "<div style='text-align: center; font-size: 20px; font-weight: bold;'>LibreAnswer Admin</div>";
	if (isset($_POST['packname']))
	{
		echo "<div style='text-align: center; font-size: 18px; font-weight: bold;'>".gettext('User answers')." - ".$_POST['packname']."</div>";
		$uam = new userAnswersManager();
		$answersResults = $uam->getUsersAnswers($_POST['packname']);
		$queManager = new questionsManager();
		$answersKey = str_repeat('0', $queManager->getQuestionsCount($_POST['packname']));
		$correctAnswers = $uam->getQuestionPackAnswers($_POST['packname']);
		$correctAnswersI = 0;
		while ($row = $correctAnswers->fetch_assoc()) {
			$answersKey[$correctAnswersI] = $row['correctAnswer'];
			$correctAnswersI++;
		}
		$answersKeyABCD = '';
		for ($i=0; $i<strlen($answersKey); $i++)
		{
			$answersKeyABCD[$i] = chr(96 + $answersKey[$i]);
		}
		echo "<div style='text-align: center; font-size: 17px; background-color: gainsboro; color: steelblue;'>".gettext('Answers key:')." <span style='font-weight: bold; letter-spacing: 3px;'>";
		for ($i=0; $i<strlen($answersKey); $i++)
		{
			echo $answersKeyABCD[$i];
		}
		echo "</span></div>";
		while ($row = $answersResults->fetch_assoc()) {
			echo "<div style='text-align: center; font-size: 17px; background-color: gainsboro;'>";
			echo "<span style='font-weight: bold'>";
			echo htmlentities($row['username']);
			echo '</span>';
			echo ' - ';
			echo "<span style='letter-spacing: 3px'>";
			$userAnswersRow = $row['userAnswers'];
			$userAnswersRow = substr($userAnswersRow, 1);
			$userCorrectAnswers = 0;
			for ($i=0; $i<strlen($userAnswersRow); $i++)
			{
				if ($userAnswersRow[$i] == '0')
				{
					echo "<span style='color: red'>?</span>";
				}
				else
				{
					if ($userAnswersRow[$i] == $answersKey[$i])
					{
						echo "<span style='color: green'>";
						$userCorrectAnswers++;
					}
					else
					{
						echo "<span style='color: red'>";
					}
					echo chr(96 + $userAnswersRow[$i]);
					echo "</span>";
				}
			}
			echo '</span>';
			echo "<span style='margin-left: 10px; font-weight: bold;'>".gettext('Correct answers:')." ".$userCorrectAnswers." (".round(($userCorrectAnswers * 100 / strlen($userAnswersRow)), 1)."%)</span>";
			echo " <form action='action.php' method='post' style='display: inline'><input type='hidden' name='action' value='clearSingleAnswer'><input type='hidden' name='packname' value='".$_POST['packname']."'><input type='hidden' name='answerID' value='".$row['answerID']."'><input type='submit' style='background-color: lightcoral; border-radius: 20px; border: 0px; cursor: pointer;' value='X'></form>";
			echo '</div>';
		}
		echo "<div style='text-align: center; font-size: 17px; background-color: gainsboro;'>";
		echo "<form action='action.php' method='post' style='display: inline'><input type='hidden' name='action' value='clearUserAnswers'><input type='hidden' name='packname' value='".$_POST['packname']."'><input type='submit' style='background-color: gainsboro; border: 1px solid black; cursor: pointer; font-size: 15px; color: red;' value='".gettext("Clear all user answers")."'></form>";
		echo '</div>';
	}
	else
	{
		echo "<div style='text-align: center; font-size: 18px; font-weight: bold;'>".gettext('No packname specified!')."</div>";
	}
	echo "<a href='panel.php' style='color: black; float: right; font-size: 16px; font-weight: bold;'>".gettext('Return to homepage')."</a>";
}
else
{
	echo gettext('Not logged in!');
}
?>
</body>
</html>
