<?php
// Translations
require_once 'lang.php';
// Main
require_once 'private/main.php';
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
<!-- Header -->
<div style='font-size: 40px; text-align: center; background-color: #C1B596; font-weight: bold; border-radius: 10px;'><a href='index.php' style='color: black; text-decoration: none;'>LibreAnswer</a></div>
<div style='text-align: center;'>
<form action='startgame.php' method='post' id='packSelectionForm'>
<div style='font-size: 18px; font-weight: bold;'><?php echo gettext("Select a question pack:") ?></div>
<?php
$quePacksManager = new questionPacksManager();
$results = $quePacksManager->getQuestionPacks();
//Question packs
while ($row = $results->fetch_assoc())
{
	if ($row['packType'] == 'standard')
	{
		echo "<span style='background-color: lightsteelblue' id='packTab'><label><input type='radio' name='packname' onclick='disableUsernameBox()' value='".$row['packname']."'><b>".$row['packDisplayName']."</b></label><br>".gettext("Author:")." ".$row['packAuthor']."<br>".gettext("Language:")." ".$row['packLanguage']."<br>".gettext("Pack type:")." ".$row['packType']."<br><button class='detailsButton' id='detailsButton_".$row['packname']."' type='button' onclick=\"showDetails('".$row['packname']."')\">".gettext('Show details')."</button><span class='packDetails_".$row['packname']."' style='visibility: hidden; display: none;'><br>".gettext("License:")." ".$row['license']."<br>".gettext("Description:")." ".$row['packDescription']."</span></span>";
	}
	else if ($row['packType'] == 'test')
	{
		echo "<span style='background-color: orange' id='packTab'><label><input type='radio' name='packname' onclick='enableUsernameBox()' value='".$row['packname']."'><b>".$row['packDisplayName']."</b></label><br>".gettext("Author:")." ".$row['packAuthor']."<br>".gettext("Language:")." ".$row['packLanguage']."<br>".gettext("Pack type:")." ".$row['packType']."<br><button class='detailsButton' id='detailsButton_".$row['packname']."' type='button' onclick=\"showDetails('".$row['packname']."')\">".gettext('Show details')."</button><span class='packDetails_".$row['packname']."' style='visibility: hidden; display: none;'><br>".gettext("License:")." ".$row['license']."<br>".gettext("Description:")." ".$row['packDescription']."</span></span>";
	}
	else if ($row['packType'] == 'quiz')
	{
		echo "<span style='background-color: lime' id='packTab'><label><input type='radio' name='packname' onclick='disableUsernameBox()' value='".$row['packname']."'><b>".$row['packDisplayName']."</b></label><br>".gettext("Author:")." ".$row['packAuthor']."<br>".gettext("Language:")." ".$row['packLanguage']."<br>".gettext("Pack type:")." ".$row['packType']."<br><button class='detailsButton' id='detailsButton_".$row['packname']."' type='button' onclick=\"showDetails('".$row['packname']."')\">".gettext('Show details')."</button><span class='packDetails_".$row['packname']."' style='visibility: hidden; display: none;'><br>".gettext("License:")." ".$row['license']."<br>".gettext("Description:")." ".$row['packDescription']."</span></span>";
	}
	else
	{
		echo "<span style='background-color: lightgray; vertical-align: top; border: 1px solid black; border-radius: 5px; display: inline-block; margin-bottom: 5px; padding: 5px; margin: 5px;'><label><input type='radio' name='packname' value='".$row['packname']."'><b>".$row['packDisplayName']."</b></label><br>".gettext("Author:")." ".$row['packAuthor']."<br>".gettext("Language:")." ".$row['packLanguage']."<br>".gettext("Pack type:")." ".$row['packType']."<br><button class='detailsButton' id='detailsButton_".$row['packname']."' type='button' onclick=\"showDetails('".$row['packname']."')\">".gettext('Show details')."</button><span class='packDetails_".$row['packname']."' style='visibility: hidden; display: none;'><br>".gettext("License:")." ".$row['license']."<br>".gettext("Description:")." ".$row['packDescription']."</span></span>";
	}
}
?>
<br>
<!-- Username input -->
<div id='usernameDiv' style='visibility: hidden; display: none;'>
<div style='font-weight: bold'><?php echo gettext('Username:') ?></div>
<input type='text' name='username' id='usernameInput'>
</div>
<!-- OK button -->
<input type='submit' value='<?php echo gettext('Start!') ?>' style='width: 70px; height: 46px; font-weight: bold;' id='okButton'>
</form>
</div>
<script>
function showDetails(packname)
{
	document.getElementById('packSelectionForm').getElementsByClassName('packDetails_' + packname)[0].style.visibility = 'visible';
	document.getElementById('packSelectionForm').getElementsByClassName('packDetails_' + packname)[0].style.display = 'inline';
	document.getElementById('detailsButton_' + packname).innerHTML = <?php echo "'".gettext('Hide details')."'" ?>;
	document.getElementById('detailsButton_' + packname).onclick = function() {
		hideDetails(packname);
		};
}

function hideDetails(packname)
{
	document.getElementById('packSelectionForm').getElementsByClassName('packDetails_' + packname)[0].style.visibility = 'hidden';
	document.getElementById('packSelectionForm').getElementsByClassName('packDetails_' + packname)[0].style.display = 'none';
	document.getElementById('detailsButton_' + packname).innerHTML = <?php echo "'".gettext('Show details')."'" ?>;
	document.getElementById('detailsButton_' + packname).onclick = function() {
		showDetails(packname);
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
