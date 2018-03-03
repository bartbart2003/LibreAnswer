<?php
// Translations
require_once 'lang.php';
?>
<html>
<head>
<title><?php echo gettext('LibreAnswer - About') ?></title>
<link rel="shortcut icon" href="favicon.ico" >
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
</head>
<body>
<div style='text-align: center; font-size: 25px;'>LibreAnswer</div>
<div style='text-align: center; font-size: 16px;'><?php echo gettext("A game inspired by the 'Who Wants to Be a Millionaire?' TV show.") ?></div>
<div style='text-align: center; font-size: 16px;'><?php echo gettext('Version that this server is using is') ?> <span style='font-weight: bold'>v0.2</span>.</div>
<div style='text-align: center; font-size: 16px;'><?php echo gettext('Webpage:') ?> <a href='http://libreanswer.bartbart.pl'>LibreAnswer</a></div>
<div style='text-align: center; font-size: 16px;'><?php echo gettext('Source code:') ?> <a href='https://github.com/bartbart2003/libreanswer'><?php echo gettext('LibreAnswer repo on GitHub') ?></a></div>
<div style='text-align: center; font-size: 16px;'><?php echo gettext('License') ?>/License: <a href='LICENSE'>LICENSE</a></div>
<div style='text-align: center; font-size: 16px;'><?php echo gettext('LibreAnswer is created by bartbart2003 and SildiN.') ?></div>
</body>
</html>
