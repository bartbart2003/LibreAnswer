<!DOCTYPE html>
<?php
// Translations
require_once 'lang.php';
?>
<html>
<head>
<title><?php echo gettext('LibreAnswer Admin - Login Page') ?></title>
<link rel='stylesheet' type='text/css' href='css/style.css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<?php include 'stats.php'; ?>
</head>
<body>
<div style='text-align: center; font-size: 20px; font-weight: bold;'><?php echo gettext('LibreAnswer Admin - Login Page') ?></div>
<form method='post' action='login.php' style='text-align: center'>
<?php echo gettext('Password:') ?> <input type='password' name='password' id='textInput'><br>
<input type='submit' value="<?php echo gettext('Login') ?>" id='okButton' style='padding: 8px;'>
</form>
</body>
</html>
