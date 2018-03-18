<!DOCTYPE html>
<?php
// Translations
require_once 'lang.php';
?>
<html>
<head>
<title>LibreAnswer</title>
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
</head>
<body>
<div style='text-align: center;'>
<!-- Social -->
<div id='socialDiv'>
<div style='display: inline-block; width: 10px;'></div><img src='img/github.png' style='width: 25px; height: 25px; cursor: pointer;' onclick="window.location.href = 'https://github.com/bartbart2003/libreanswer'" alt='GitHub' />
<img src='img/website.png' style='width: 25px; height: 25px; cursor: pointer;' onclick="window.location.href = 'http://libreanswer.bartbart.pl'" alt='Website' />
<img src='img/mail.png' style='height: 20px; cursor: pointer;' onclick="window.location.href = 'mailto:libreanswer@bartbart.pl'" alt='E-mail' />
</div><br>
<!-- Title -->
<div id='titleDiv'>
<span style='color: white'>Libre</span><span style='color: cyan'>A</span><span style='color: yellow'>n</span><span style='color: orange'>s</span><span style='color: cyan'>w</span><span style='color: yellow'>e</span><span style='color: orange'>r</span>
</div><br>
<!-- Version and text lines -->
<div id='homepageTextDiv' style='background-color: burlywood; font-size: 16px;'>v0.3.1</div><br>
<div id='homepageTextDiv'><?php echo gettext('Select a question pack.') ?></div><br>
<div id='homepageTextDiv'><?php echo gettext('Answer the questions.') ?></div><br>
<div id='homepageTextDiv' style='border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;'><?php echo gettext('Check your knowedgle!') ?></div><br>
</div>
<div style='text-align: center; margin: 10px;'><a href='selectpack.php' id='playLink'><?php echo gettext("Let's go!") ?></a></div>
<div style='text-align: center; font-size: 12px;'><?php echo gettext('or...') ?></div>
<div style='text-align: center; margin: 5px; font-size: 13px;'><a href="https://github.com/bartbart2003/LibreAnswer/wiki/Introduction" style='color: black'><?php echo gettext('learn how to create your own question pack') ?></a></div>
<div style='text-align: right; font-size: 11px;'><?php echo gettext('by bartbart2003 and SildiN') ?> | <a href='about.php' style='color: black'><?php echo gettext('About') ?></a> | <a href='https://github.com/bartbart2003/libreanswer/issues' style='color: black'><?php echo gettext('Found a bug? Have an idea? Click here!') ?></a></div>
</body>
</html>
