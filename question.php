<?php
session_start();
isset($_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
$questionUserAnswer = '';
if ($_SESSION['packType'] == 'test')
{ 
	$questionUserAnswer = $_SESSION['userAnswers'][$_SESSION['questionNumber']];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer</title>
<script src='js/jquery.js'></script>
<link rel="shortcut icon" href="favicon.ico" >
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<?php
if ($_SESSION['backgroundsEnabled'] == true)
{
	echo "<style>";
	include 'randombg.php';
	echo "</style>";
}
?>
<?php include 'stats.php'; ?>
</head>
<body>
<?php
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
$questionText = '';
$queManager = new questionsManager();
$row = $queManager->getQuestion($_SESSION['packname'], $_SESSION['questionNumber']);
$questionsCount = $queManager->getQuestionsCount($_SESSION['packname']);
?>
<div style='background-color: #C1B596; text-align: center; font-size: 21px; border-radius: 10px;'><?php echo gettext('LibreAnswer - Question') ?> <?php echo $_SESSION['questionNumber'] ?> <?php echo gettext('of') ?> <?php echo $questionsCount ?></div>
<!-- Lifelines -->
<div style='background-color: aquamarine; text-align: center; font-size: 20px; border-radius: 10px; width: 240px; margin: 0 auto; padding-left: 15px; padding-right: 15px; margin-bottom: 5px;'><?php if (strpos($_SESSION['lifelines'],'h') !== false) { echo "<button onclick='getHint();' id='lifelineHintButton'>".gettext('HINT')."</button>"; } ?><?php if (strpos($_SESSION['lifelines'],'f') !== false && $row['questionType'] != 'tf') { echo "<button onclick='getFF();' id='lifelineFFButton'>".gettext('50/50')."</button>"; } ?></div>
<!-- Question text -->
<div style='text-align: center; font-size: 21px; font-weight: bold;'><?php echo htmlentities($row['question']) ?></div>
<!-- Hint div -->
<div id='hintDiv' style='visibility: hidden; text-align: center; background-color: lime; border-radius: 10px; font-size: 18px;'></div>
<form method='get' style='text-align: center;' id='answersForm'>
<?php
// ABCD answers
if ($row['questionType'] == 'abcd')
{
	for ($i = 1; $i<5; $i++)
	{
		echo "<label><span id='questionTab' class='answer".strtoupper(chr(96 + $i))."FormSpan'><input type='radio' name='formAnswer' value='".$i."' id='formAnswer".$i."' ";
		if ($questionUserAnswer == $i)
		{
			echo 'checked';
		}
		echo "><b>".chr(96 + $i)."</b> ".htmlentities($row['answer'.$i])."</span></label>";
		if ($i == 2 || $i == 4)
		{
			echo "<br>";
		}
	}
}

// True/false answers
if ($row['questionType'] == 'tf')
{
	echo "<div style='display: inline-block; text-align: center; width: 400px; max-width: 90vw;'>";
	for ($i = 1; $i<5; $i++)
	{
		echo "<div style='margin-bottom: 5px; border: 1px solid black;'>";
		echo "<div style='background-color: lightsteelblue; margin-bottom: 2px; padding-left: 5px; padding-right: 5px;'>";
		echo htmlentities($row['answer'.$i]);
		echo "</div>";
		echo "<div style='margin-bottom: 2px;'>";
		echo "<label><div id='tfDiv' style='background-color: lightgreen'><input type='radio' name='tfAnswer".$i."' value='t' id='tfAnswer".$i."'>".gettext("True")."</div></label>";
		echo "<label><div id='tfDiv' style='background-color: lightcoral'><input type='radio' name='tfAnswer".$i."' value='f' id='tfAnswer".$i."'>".gettext("False")."</div></label>";
		echo "</div>";
		echo "</div>";
	}
	echo "</div>";
	echo "<br>";
}
// Submit buttons
if ($_SESSION['packType'] == 'standard' || $_SESSION['packType'] == 'quiz')
{
	echo "<input type='submit' value='OK' style='margin: 6px;' id='okButton' formaction='check.php'>";
}
else if ($_SESSION['packType'] == 'test')
{
	echo "<span onclick='resetRadioButtons();' style='border: 0px; background-color: white; text-decoration: underline; cursor: pointer; color: black;'>".gettext('Clear answer')."</span><br>";
	if ($_SESSION['questionNumber'] == 1)
	{
		echo "<input type='submit' value='< ".gettext('Previous question')."' formaction='prev.php' style='margin-top: 6px; padding: 5px; background-color: gainsboro;' id='okButton'>";
	}
	else
	{
		echo "<input type='submit' value='< ".gettext('Previous question')."' formaction='prev.php' style='margin-top: 6px; padding: 5px;' id='okButton'>";
	}
	if ($_SESSION['questionNumber'] == $questionsCount)
	{
		echo "<br><input type='submit' value='".gettext('End test')."' formaction='next.php' id='endTestButton'> <span style='font-weight: bold'><br>".gettext("Be careful when ending the test! This action can't be undone!");
	}
	else
	{
		echo " | <input type='submit' value='".gettext('Next question')." >' formaction='next.php' style='margin-top: 6px; padding: 5px;' id='okButton'>";
	}
}
if ($_SESSION['packType'] == 'standard' || $_SESSION['packType'] == 'quiz')
{
	echo "<br><br><a href='endgame.php' style='color: black; font-size: 16px;'>".gettext('Abort game')."</a>";
}
if ($_SESSION['packType'] == 'test')
{
	echo "<br><br><a href='endgame.php' style='color: black; font-size: 16px;'>".gettext('Abort test')."</a>";
}
?>
</form>
<script>
function getHint()
{
	document.getElementById('lifelineHintButton').style.visibility = 'hidden';
	$.get('hint.php', function(data)
	{
	document.getElementById('hintDiv').innerHTML = <?php echo "'".gettext('Hint:')." '" ?> + data;
	document.getElementById('hintDiv').style.visibility = 'visible';
	});
}

function getFF()
{
	document.getElementById('lifelineFFButton').style.visibility = 'hidden';
	$.get('fifty.php', function(data)
	{
		document.getElementById('answersForm').getElementsByClassName('answer' + data[0].toUpperCase() + 'FormSpan')[0].style.backgroundColor = 'red';
		document.getElementById('answersForm').getElementsByClassName('answer' + data[1].toUpperCase() + 'FormSpan')[0].style.backgroundColor = 'red';
	});
}

function resetRadioButtons()
{
	document.getElementById('formAnswer1').checked = false;
	document.getElementById('formAnswer2').checked = false;
	document.getElementById('formAnswer3').checked = false;
	document.getElementById('formAnswer4').checked = false;
}
</script>
</body>
</html>
