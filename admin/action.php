<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer Admin</title>
<link rel='stylesheet' type='text/css' href='css/style.css'><meta name='viewport' content='width=device-width, initial-scale=1'>
<?php include 'stats.php'; ?>
</head>
<body>
<?php
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
{
	if ($_POST['action'] == 'deletePack')
	{
		if (isset($_POST['packname']) && $_POST['packname'] != '')
		{
			$quePackManager = new questionPacksManager();
			$deleteUserAnswers = false;
			$quePackManager->deleteQuestionPack($_POST['packname']);
			echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Pack deleted.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
		}
		else
		{
			echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('No packname specified!')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
		}

	}
	if ($_POST['action'] == 'importPack')
	{
		if (isset($_POST['location']) && $_POST['location'] == 'local' && isset ($_POST['packFile']) && file_exists('import/'.$_POST['packFile']) || isset($_POST['location']) && $_POST['location'] == 'remote')
		{
			$quePackManager = new questionPacksManager();
			$importStatus = $quePackManager->importQuestionPack($_POST['location'], $_POST['packFile']);
			if ($importStatus == 'ok')
			{
				echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Pack imported.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
			}
			elseif ($importStatus == 'eemptyname')
			{
				echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Error: Empty packname.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
			}
			else
			{
				echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Error importing pack.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
			}
		}
		else
		{
			echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Error: File not found.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
		}
	}
	if ($_POST['action'] == 'clearUserAnswers')
	{
		$quePackManager = new questionPacksManager();
		$quePackManager->clearUserAnswers($_POST['packname']);
		echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('User answers cleared.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
	}
	if ($_POST['action'] == 'clearSingleAnswer')
	{
		$quePackManager = new questionPacksManager();
		$quePackManager->clearSingleAnswer($_POST['packname'], $_POST['answerID']);
		echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Answer cleared.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
	}
}
else
{
	echo gettext('Not logged in!');
}
?>
</body>
</html>
