<?php
// Main
require_once 'private/main.php';
session_start();
echo 'Starting game...';
(isset($_POST['packname']) && $_POST['packname'] != '') or die("<br>Error: No question pack specified! <a href='selectpack.php'>Return</a>");
(isset($_POST['username']) && $_POST['username'] != '') or die("<br>Error: No username specified! <a href='selectpack.php'>Return</a>");
$quePackManager = new questionPacksManager();
$results = $quePackManager->getQuestionPacks();
$packnameForSession = '';
$packType = 'standard';
while ($row = $results->fetch_assoc())
{
	if ($row['packname'] == $_POST['packname'])
	{
		$packnameForSession = $_POST['packname'];
		$packType = $row['packType'];
		break;
	}
}
if ($packnameForSession == '')
{
	die("<br>Error: Question pack not found! <a href='selectpack.php'>Return</a>");
}
$_SESSION['username'] = $_POST['username'];
$_SESSION['packname'] = $packnameForSession;
$_SESSION['packType'] = $packType;
$_SESSION['gameStarted'] = 'true';
$_SESSION['questionNumber'] = '1';
$_SESSION['formAnswer'] = '1';
if ($packType == 'standard')
{
	$_SESSION['lifelines'] = 'hf';
}
else if ($packType == 'test')
{
	$_SESSION['lifelines'] = '';
	$queManager = new questionsManager(); 
	$_SESSION['userAnswers'] = str_repeat('0', $queManager->getQuestionsCount($_SESSION['packname']) + 1);
	$_SESSION['answersSaved'] = false;
}
else if ($packType == 'quiz')
{
	$_SESSION['lifelines'] = 'hf';
	$_SESSION['correctUserAnswers'] = 0;
}
echo "<script>window.location.href = 'question.php';</script>";
?>
