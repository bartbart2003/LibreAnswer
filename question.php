<?php
// Main
require_once 'private/main.php';

session_start();

$queManager = new questionsManager();
($queManager->getContentCount($_SESSION['packname']) >= $_SESSION['contentNumber']) or die("<script>window.location.href = 'afterlast.php'</script>");
$row = $queManager->getContent($_SESSION['packname'], $_SESSION['contentNumber']);
(isset($_SESSION['gameStarted']) && $_SESSION['gameStarted']) or die("Error: Game not started!<br><a href='index.php'>Return</a>");
($row['contentType'] == 'abcd' || $row['contentType'] == 'tf' || $row['contentType'] == 'info') or die("Error: Invalid content type!");
($row['contentType'] != 'info') or die("<script>window.location.href = 'next.php?contentNumber=".$_SESSION['contentNumber']."'</script>");
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
if ($_SESSION['backgroundsEnabled'])
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
$questionText = '';
$questionsCount = $queManager->getQuestionsCount($_SESSION['packname']);
?>
<div style='background-color: #C1B596; text-align: center; font-size: 21px; border-radius: 10px;'><?php echo gettext('LibreAnswer - Question') ?> <?php echo $_SESSION['questionNumber'] ?> <?php echo gettext('of') ?> <?php echo $questionsCount ?></div>
<!-- Lifelines -->
<div style='background-color: aquamarine; text-align: center; font-size: 20px; border-radius: 10px; width: 240px; margin: 0 auto; padding-left: 15px; padding-right: 15px; margin-bottom: 5px;'><?php if (strpos($_SESSION['lifelines'],'h') !== false) { echo "<button onclick='getHint();' id='lifelineHintButton'>".gettext('HINT')."</button>"; } ?><?php if (strpos($_SESSION['lifelines'],'f') !== false && $row['contentType'] != 'tf') { echo "<button onclick='getFF();' id='lifelineFFButton'>".gettext('50/50')."</button>"; } ?></div>
<!-- Multimedia -->
<?php
// YouTube video
if ($row['multimediaType'] == 'yt')
{
	echo "<div style='margin: auto; text-align: center;'>";
	echo "<iframe width='400' height='200' src='https://www.youtube.com/embed/".$row['multimediaContent']."?rel=0' frameborder='0' allow='encrypted-media' allowfullscreen></iframe>";
	echo "</div>";
}
// Image
if ($row['multimediaType'] == 'img')
{
	echo "<div style='margin: auto; text-align: center;'>";
	echo "<img height='200' src='".$row['multimediaContent']."' />";
	echo "</div>";
}
// Video
if ($row['multimediaType'] == 'vid')
{
	echo "<div style='margin: auto; text-align: center;'>";
	echo "<video width='400' height='200' controls src='".$row['multimediaContent']."' style='background-color: black'></video>";
	echo "</div>";
}
?>
<!-- Question text -->
<div style='text-align: center; font-size: 21px; font-weight: bold;'><?php echo htmlentities($row['question']) ?></div>
<!-- Hint div -->
<div id='hintDiv' style='visibility: hidden; text-align: center; background-color: lime; border-radius: 10px; font-size: 18px;'></div>
<form method='get' style='text-align: center;' id='answersForm' action='next.php'>
<?php
// Content number
echo "<input type='hidden' name='contentNumber' value='".$_SESSION['contentNumber']."'>";

// ABCD answers
if ($row['contentType'] == 'abcd')
{
	for ($i = 1; $i<5; $i++)
	{
		echo "<label><span id='questionTab' class='answer".strtoupper(chr(96 + $i))."FormSpan'><input type='radio' name='formAnswer' value='".$i."' id='formAnswer".$i."' ";
		echo "><b>".chr(96 + $i)."</b> ".htmlentities($row['answer'.$i])."</span></label>";
		if ($i == 2 || $i == 4)
		{
			echo "<br>";
		}
	}
}

// True/false answers
if ($row['contentType'] == 'tf')
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
echo "<input type='submit' value='OK' style='margin: 6px;' id='okButton'>";
echo "<br><br><a href='endgame.php' style='color: black; font-size: 16px;'>".gettext('Abort game')."</a>";
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
