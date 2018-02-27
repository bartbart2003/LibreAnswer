<?php
session_start();
header('Content-type: text/html; charset=utf-8');
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
			if (isset($_POST['deleteua']) && $_POST['deleteua'] == 'true')
			{
				$deleteUserAnswers = true;
			}
			$quePackManager->deleteQuestionPack($_POST['packname'], $deleteUserAnswers);
			echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Pack deleted.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
		}
		else
		{
			echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('No packname specified!')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
		}

	}
	if ($_POST['action'] == 'importPack')
	{
		if (file_exists('import/'.$_POST['packFilename']))
		{
			$quePackManager = new questionPacksManager();
			$quePackManager->importQuestionPack($_POST['packFilename']);
			echo "<div style='text-align: center; font-size: 16px; font-weight: bold; '>".gettext('Pack imported.')." <a href='panel.php' style='color: black'>".gettext('Return to homepage')."</a></div>";
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
