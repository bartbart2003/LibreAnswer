<?php
session_start();
session_destroy();
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
echo gettext('Logout successful!').'<br>';
echo "<a href='login_form.php'>".gettext('Login form').'</a>';
?>
</body>
</html>
