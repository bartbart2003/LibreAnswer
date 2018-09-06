<!DOCTYPE html>
<?php
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
?>
<html>
<head>
<!-- Icons from Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<title>LibreAnswer</title>
<script src='js/jquery.js'></script>
<link rel="shortcut icon" href="favicon.ico" >
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<style>
body {
	background-image: url('img/notebook.png');
	/* Background pattern from Subtle Patterns */
}
</style>
<?php include 'stats.php'; ?>
</head>
<body>
<!-- Header -->
<div style='font-size: 40px; text-align: center; background-color: #C1B596; font-weight: bold; border-radius: 10px;'><a href='index.php' style='color: black; text-decoration: none;'>LibreAnswer</a></div>
<div style='text-align: center;'>
<form action='startgame.php' method='post' id='packSelectionForm'>
<div style='font-size: 18px; font-weight: bold;'><?php echo gettext("Select a question pack:") ?></div>
<?php
$quePacksManager = new questionPacksManager();
$results = $quePacksManager->getQuestionPacks();
// Question packs
$i = 0;
while ($row = $results->fetch_assoc())
{
	$i++;
	echo "<span style='background-color: ";
	if (strpos($row['packAttributes'],'m') !== false)
	{
		echo '#FC5F44';
	}
	else
	{
		echo 'lime';
	}
	echo "' id='packTab'><label><div style='display: inline-block; width: 100%;'>";
	// FontAwesome icon
	if ($row['packIconType'] == 'fa')
	{
		echo "<span class='fas fa-";
		echo htmlentities($row['packIcon']);
		echo "' style='display: inline-block; font-size: 30px; background-color: antiquewhite; border-radius: 10px; padding: 8px;'></span>";
	}
	// Image
	else if ($row['packIconType'] == 'img')
	{
		echo "<span style='display: inline-block; background-color: antiquewhite; background-image: url(\"".htmlentities($row['packIcon'])."\"); background-repeat: no-repeat; background-size: cover; width: 40px; height: 40px; border-radius: 10px; padding: 8px;'></span>";
	}
	// Default
	else
	{
		echo "<span class='fas fa-box-open' style='display: inline-block; font-size: 30px; background-color: antiquewhite; border-radius: 10px; padding: 8px;'></span>";
	}
	echo "</div><input type='radio' name='packname' onclick='disableUsernameBox()'";
	echo " value='".htmlentities($row['packname'])."'><b>".htmlentities($row['packDisplayName'])."</b></label><br><div style='display: inline-block; width: 100%; border-bottom: 1px solid black; font-size: 14px; margin-bottom: 6px;'>".htmlentities($row['packAuthor'])."</div><br><div style='font-size: 10px; text-align: left; display: block; width: 100%;'>";
	if (strpos($row['packAttributes'],'f') !== false && strpos($row['packAttributes'],'h') !== false)
	{
		echo "50/50, ".gettext("hint");
	}
	else if (strpos($row['packAttributes'],'h') !== false)
	{
		echo 'hint';
	}
	else if (strpos($row['packAttributes'],'f') !== false)
	{
		echo gettext("50/50");
	}
	else
	{
		echo gettext("no lifelines");
	}
	echo "</div>";
	echo gettext("Language:")." ".htmlentities($row['packLanguage'])."<br><button class='descButton' id='descButton_".htmlentities($row['packname'])."' type='button' onclick=\"showDesc('".htmlentities($row['packname'])."')\">".gettext('Show description')."</button><span class='packDesc_".htmlentities($row['packname'])."' style='visibility: hidden; display: none;'><br>".gettext("Description:")." ".htmlentities($row['packDescription'])."</span></span>";
}
if ($i == 0)
{
	echo "<div style='font-size: 20px; margin: 20px;'>".gettext("No question packs available")."</div>";
}
?>
<br>
<!-- Username input -->
<div id='usernameDiv' style='visibility: hidden; display: none;'>
<div style='font-weight: bold'><?php echo gettext('Username:') ?></div>
<input type='text' name='username' id='usernameInput'>
</div>
<!-- OK button -->
<input type='submit' value='<?php echo gettext('Start!') ?>' style='width: 85px; height: 55px; font-weight: bold;' id='okButton'>
</form>
</div>
<script>
// Description
function showDesc(packname)
{
	document.getElementById('packSelectionForm').getElementsByClassName('packDesc_' + packname)[0].style.visibility = 'visible';
	document.getElementById('packSelectionForm').getElementsByClassName('packDesc_' + packname)[0].style.display = 'inline';
	document.getElementById('descButton_' + packname).innerHTML = <?php echo "'".gettext('Hide description')."'" ?>;
	document.getElementById('descButton_' + packname).onclick = function() {
		hideDesc(packname);
		};
}

function hideDesc(packname)
{
	document.getElementById('packSelectionForm').getElementsByClassName('packDesc_' + packname)[0].style.visibility = 'hidden';
	document.getElementById('packSelectionForm').getElementsByClassName('packDesc_' + packname)[0].style.display = 'none';
	document.getElementById('descButton_' + packname).innerHTML = <?php echo "'".gettext('Show description')."'" ?>;
	document.getElementById('descButton_' + packname).onclick = function() {
		showDesc(packname);
		};
}

function enableUsernameBox()
{
	document.getElementById('usernameDiv').style.visibility = 'visible';
	document.getElementById('usernameDiv').style.display = 'block';
	okEnableDisable();
}

function disableUsernameBox()
{
	document.getElementById('usernameDiv').style.visibility = 'hidden';
	document.getElementById('usernameDiv').style.display = 'none';
	document.getElementById('okButton').disabled = false;
}

// Uncheck all radio buttons
$("input[name='packname']").prop('checked', false);

onload = function () {
	var e = document.getElementById('usernameInput');
	e.oninput = okEnableDisable;
	e.onpropertychange = e.oninput;
	e.onchange = e.oninput;
};

function okEnableDisable()
{
	if (document.getElementById('usernameInput').value == '')
	{
		document.getElementById('okButton').disabled = true;
	}
	else
	{
		document.getElementById('okButton').disabled = false;
	}
}

// Clear username input and disable ok button
document.getElementById('usernameInput').value = '';
document.getElementById('okButton').disabled = true;
</script>
</body>
</html>
