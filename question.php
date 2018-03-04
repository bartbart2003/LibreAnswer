<!DOCTYPE html>
<?php
session_start();
isset($_SESSION['gameStarted']) or die ("Error: Game not started!<br><a href='index.php'>Return</a>");
$questionUserAnswer = '';
if ($_SESSION['packType'] == 'test')
{ 
	$questionUserAnswer = $_SESSION['userAnswers'][$_SESSION['questionNumber']];
}
?>
<html>
<head>
<title>LibreAnswer</title>
<script src='js/jquery.js'></script>
<link rel="shortcut icon" href="favicon.ico" >
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
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
<div style='background-color: #C1B596; text-align: center; font-size: 20px; border-radius: 10px;'><?php echo gettext('LibreAnswer - Question') ?> <?php echo $_SESSION['questionNumber'] ?> <?php echo gettext('of') ?> <?php echo $questionsCount ?></div>
<!-- Lifelines -->
<div style='background-color: aquamarine; text-align: center; font-size: 20px; border-radius: 10px; width: 240px; margin: 0 auto; padding-left: 15px; padding-right: 15px;'><?php if (strpos($_SESSION['lifelines'],'h') !== false) { echo "<button onclick='getHint();' id='lifelineHintButton'>".gettext('HINT')."</button>"; } ?><?php if (strpos($_SESSION['lifelines'],'f') !== false) { echo "<button onclick='getFF();' id='lifelineFFButton'>".gettext('50/50')."</button>"; } ?></div>
<!-- Question text -->
<div style='text-align: center; font-size: 20px; font-weight: bold;'><?php echo $row['question'] ?></div>
<!-- Hint div -->
<div id='hintDiv' style='visibility: hidden; text-align: center; background-color: lime; border-radius: 10px;'></div>
<form method='get' style='text-align: center;' id='answersForm'>
<!-- ABCD Answers -->
<label><span id='questionTab' class='answerAFormSpan'><input type='radio' name='formAnswer' value='1' id='formAnswer1' <?php if ($questionUserAnswer == '1') { echo 'checked'; } ?>><b>a</b> <?php echo htmlentities($row['answer1']) ?></span></label>
<label><span id='questionTab' class='answerBFormSpan'><input type='radio' name='formAnswer' value='2' id='formAnswer2' <?php if ($questionUserAnswer == '2') { echo 'checked'; } ?>><b>b</b> <?php echo htmlentities($row['answer2']) ?></span></label><br> 
<label><span id='questionTab' class='answerCFormSpan'><input type='radio' name='formAnswer' value='3' id='formAnswer3' <?php if ($questionUserAnswer == '3') { echo 'checked'; } ?>><b>c</b> <?php echo htmlentities($row['answer3']) ?></span></label>
<label><span id='questionTab' class='answerDFormSpan'><input type='radio' name='formAnswer' value='4' id='formAnswer4' <?php if ($questionUserAnswer == '4') { echo 'checked'; } ?>><b>d</b> <?php echo htmlentities($row['answer4']) ?></span></label><br>
<?php
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
	echo "<br><a href='endgame.php' style='color: black; font-size: 16px;'>".gettext('Abort game')."</a>";
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
