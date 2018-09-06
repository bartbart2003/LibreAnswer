<?php
// Main
require_once 'private/main.php';
(isset($_POST['packname']) && $_POST['packname'] != '') or die("<br>Error: No question pack specified! <a href='selectpack.php'>Return</a>");
// Ensure that we're starting a new session
session_start();
session_destroy();
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer</title>
<?php include 'stats.php'; ?>
</head>
<body>
<?php
echo 'Starting game...';
$quePackManager = new questionPacksManager();
$results = $quePackManager->getQuestionPacks();
$packnameForSession = '';
$packAttributes = '';
while ($row = $results->fetch_assoc())
{
	if ($row['packname'] == $_POST['packname'])
	{
		$packnameForSession = $_POST['packname'];
		$packAttributes = $row['packAttributes'];
		break;
	}
}
if ($packnameForSession == '')
{
	die("<br>Error: Question pack not found! <a href='selectpack.php'>Return</a>");
}
$_SESSION['packname'] = $packnameForSession;
$_SESSION['hardcoreMode'] = false;
$_SESSION['lifelines'] = '';
$_SESSION['backgroundsEnabled'] = false;
$_SESSION['gameStarted'] = true;
$_SESSION['questionNumber'] = '1';
$_SESSION['contentNumber'] = '1';
$_SESSION['formAnswer'] = '1';

// Attributes
$_SESSION['correctUserAnswers'] = 0;
if (strpos($packAttributes,'h') !== false)
{
	$_SESSION['lifelines'] = $_SESSION['lifelines'].'h';
}
if (strpos($packAttributes,'f') !== false)
{
	$_SESSION['lifelines'] = $_SESSION['lifelines'].'f';
}
if (strpos($packAttributes,'b') !== false)
{
	$_SESSION['backgroundsEnabled'] = true;
}
if (strpos($packAttributes,'m') !== false)
{
	$_SESSION['hardcoreMode'] = true;
}

echo "<script>window.location.href = 'question.php';</script>";
?>
</body>
</html>
