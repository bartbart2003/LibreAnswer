<?php
session_start();
session_destroy();
header('Content-type: text/html; charset=utf-8');
// Translations
require_once 'lang.php';
echo gettext('Logout successful!').'<br>';
echo "<a href='login_form.php'>".gettext('Login form').'</a>';
?>
