<?php
// This file is meant to be included in other scripts. It sets the language in gettext based on user browser config or GET parameter.
$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$langShort = 'en';
if (isset($_GET['lang']) && $_GET['lang'] != '')
{
	$lang = $_GET['lang'];
}
switch(substr($lang, 0, 2))
{
	case 'pl':
		putenv('LANG=pl_PL.UTF-8');
		setlocale(LC_ALL, 'pl_PL.UTF-8');
		$langShort = 'pl';
		break;
	default:
		break;
}
bindtextdomain('translation', 'locale');
textdomain('translation');
?>
