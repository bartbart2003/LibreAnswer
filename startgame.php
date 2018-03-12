<?php
// Main
require_once 'private/main.php';
// Ensure that we're starting a new session
session_start();
session_destroy();
session_start();
echo 'Starting game...';
(isset($_POST['packname']) && $_POST['packname'] != '') or die("<br>Error: No question pack specified! <a href='selectpack.php'>Return</a>");
$quePackManager = new questionPacksManager();
$results = $quePackManager->getQuestionPacks();
$packnameForSession = '';
$packType = 'standard';
$packAttributes = '';
while ($row = $results->fetch_assoc())
{
	if ($row['packname'] == $_POST['packname'])
	{
		$packnameForSession = $_POST['packname'];
		$packType = $row['packType'];
		$packAttributes = $row['attributes'];
		break;
	}
}
if ($row['packType'] == 'test')
{
	(isset($_POST['username']) && $_POST['username'] != '') or die("<br>Error: No username specified! <a href='selectpack.php'>Return</a>");
}
if ($packnameForSession == '')
{
	die("<br>Error: Question pack not found! <a href='selectpack.php'>Return</a>");
}
$_SESSION['username'] = $_POST['username'];
$_SESSION['packname'] = $packnameForSession;
$_SESSION['packType'] = $packType;
$_SESSION['lifelines'] = '';
$_SESSION['gameStarted'] = 'true';
$_SESSION['questionNumber'] = '1';
$_SESSION['formAnswer'] = '1';

// Type-specific things
if ($packType == 'test')
{
	$queManager = new questionsManager(); 
	$_SESSION['userAnswers'] = str_repeat('0', $queManager->getQuestionsCount($_SESSION['packname']) + 1);
	$_SESSION['answersSaved'] = false;
}
else if ($packType == 'quiz')
{
	$_SESSION['correctUserAnswers'] = 0;
}

// Attributes
if (strpos($packAttributes,'h') !== false)
{
	$_SESSION['lifelines'] = $_SESSION['lifelines'].'h';
}
if (strpos($packAttributes,'f') !== false)
{
	$_SESSION['lifelines'] = $_SESSION['lifelines'].'f';
}

echo "<script>window.location.href = 'question.php';</script>";
?>
