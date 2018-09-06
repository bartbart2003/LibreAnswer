<?php
session_start();
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
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
$queCount = $queManager->getQuestionsCount($_SESSION['packname']);
if ($_SESSION['questionNumber'] >= $queCount)
{
	if ($_SESSION['hardcoreMode'])
	{
		echo "<div style='background-color: lightgreen; text-align: center; font-size: 30px; border-top-left-radius: 10px; border-top-right-radius: 10px;'>".gettext('LibreAnswer - Congratulations!')."</div>";
		echo "<div style='background-color: gold; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('You won the game!')."</div>";
		echo "<div style='background-color: gold; text-align: center; font-size: 15px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
	}
	else
	{
		echo "<div style='background-color: lightgreen; text-align: center; font-size: 30px; border-top-left-radius: 10px; border-top-right-radius: 10px;'>LibreAnswer</div>";
		echo "<div style='background-color: aquamarine; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('You completed the quiz!')."</div>";
		echo "<div style='background-color: aquamarine; text-align: center; font-size: 20px; font-weight: bold;'>".gettext('Correct answers:')." ".$_SESSION['correctUserAnswers']." / ".$queCount." (".round(($_SESSION['correctUserAnswers'] * 100 / $queCount), 1)."%)</div>";
		echo "<div style='background-color: aquamarine; text-align: center; font-size: 15px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;'><a href='endgame.php' style='color: black'>".gettext('End game')."</a></div>";
	}
}
else
{
	echo "Quiz not completed!";
}
?>
</body>
</html>
