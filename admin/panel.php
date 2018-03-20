<?php
session_start();
header('Content-type: text/html; charset=utf-8');
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
echo "<html><head><title>LibreAnswer Admin</title><link rel='stylesheet' type='text/css' href='css/style.css'><meta name='viewport' content='width=device-width, initial-scale=1'></head><body>";
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
{
	echo "<div style='text-align: center; font-size: 20px; font-weight: bold;'>LibreAnswer Admin</div>";
	echo "<div style='text-align: center; font-size: 18px; font-weight: bold;'>".gettext('Homepage')."</div>";
	$dbConnector = new databaseConnector();
	$dbConnector->connectToDatabase();
	//SECTION: show user answers
	$query = "SELECT * FROM packlist WHERE packType='test'";
	$results = $dbConnector->conn->query($query);
	echo "<div style='background-color: gainsboro; padding-top: 5px; padding-bottom: 5px; margin-top: 5px;'><div style='text-align: center; font-size: 17px; font-weight: bold;'>".gettext('User answers')."</div>";
	if ($results->num_rows > 0)
	{
		echo "<form action='answers.php' method='post' style='text-align: center;'>";
		while ($row = $results->fetch_assoc())
		{
			echo "<label><input type='radio' name='packname' value='".$row['packname']."'>".$row['packDisplayName']." (".$row['packname'].")</label><br>";
		}
		echo "<input type='submit' value='".gettext('Show user answers')."' id='okButton'>";
		echo '</form>';
	}
	else
	{
		echo "<div style='text-align: center; font-weight: bold; font-size: 16px; padding: 10px;'>".gettext("No test question packs")."</div>";
	}
	echo '</div>';
	//SECTION: clear user answers
	echo "<div style='background-color: gainsboro; padding-top: 5px; padding-bottom: 5px; margin-top: 5px;'><div style='text-align: center; font-size: 17px; font-weight: bold;'>".gettext('Clear user answers')."</div>";
	echo "<div style='text-align: center; font-size: 15px; font-weight: bold; color: red;'>".gettext('BE CAREFUL!')."</div>";
	$query = "SELECT * FROM packlist WHERE packType='test'";
	$results = $dbConnector->conn->query($query);
	if ($results->num_rows > 0)
	{
		echo "<form action='action.php' method='post' style='text-align: center;'>";
		while ($row = $results->fetch_assoc())
		{
			echo "<label><input type='radio' name='packname' value='".$row['packname']."'>".$row['packDisplayName']." (".$row['packname'].")</label><br>";
		}
		echo "<input type='hidden' name='action' value='clearUserAnswers'>";
		echo "<input type='submit' value='".gettext('Clear user answers')."' id='okButton'>";
		echo '</form>';
	}
	else
	{
		echo "<div style='text-align: center; font-weight: bold; font-size: 16px; padding: 10px;'>".gettext("No test question packs")."</div>";
	}
	echo '</div>';
	//SECTION: delete pack
	echo "<div style='background-color: gainsboro; padding-top: 5px; padding-bottom: 5px; margin-top: 5px;'><div style='text-align: center; font-size: 17px; font-weight: bold;'>".gettext('Pack deletor')."</div>";
	echo "<div style='text-align: center; font-size: 15px; font-weight: bold; color: red;'>".gettext('BE CAREFUL!')."</div>";
	$quePackManager = new questionPacksManager();
	$results = $quePackManager->getQuestionPacks();
	if ($results->num_rows > 0)
	{
		echo "<form action='action.php' method='post' style='text-align: center;'>";
		while ($row = $results->fetch_assoc())
		{
			echo "<label><input type='radio' name='packname' value='".$row['packname']."'>".$row['packDisplayName']." (".$row['packname'].")</label><br>";
		}
		echo "<div style='display: inline-block; margin-top: 3px;'><label><input type='checkbox' name='deleteua' value='true' checked>".gettext('Delete user answers')."</label></div><br>";
		echo "<input type='hidden' name='action' value='deletePack'>";
		echo "<input type='submit' value='".gettext('Delete pack')."' id='okButton'>";
		echo '</form>';
	}
	else
	{
		echo "<div style='text-align: center; font-weight: bold; font-size: 16px; padding: 10px;'>".gettext("No question packs")."</div>";
	}
	echo '</div>';
	//SECTION: import pack
	echo "<div style='background-color: gainsboro; padding-top: 5px; padding-bottom: 5px; margin-top: 5px;'><div style='text-align: center; font-size: 17px; font-weight: bold;'>".gettext('Pack import')."</div>";
	echo "<div style='text-align: center; font-size: 15px; font-weight: bold; color: red;'>".gettext("Files should be placed in the import/ directory")."</div>";
	echo "<form action='action.php' method='post' style='text-align: center;'>";
	$files = glob('import/*.aqf');
	foreach($files as $file)
	{
		$file = substr($file, 7);
		echo "<input type='radio' name='packFilename' value='".$file."'>".$file."<br>";
	}
	echo "<input type='hidden' name='action' value='importPack'>";
	echo "<input type='submit' value='".gettext('Import pack')."' id='okButton'>";
	echo '</form>';
	echo '</div>';
	echo "<a href='logout.php' style='color: black; float: right; font-size: 15px; font-weight: bold;'>".gettext("Logout")."</div>";
}
else
{
	echo gettext('Not logged in!').'<br>';
	echo "<a href='login_form.php'>".gettext('Login form').'</a>';
}
echo "</body></html>";
?>
