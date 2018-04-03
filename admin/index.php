<?php
session_start();
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
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
{
	echo "<script>window.location.href = 'panel.php'</script>";
}
else
{
	echo "<script>window.location.href = 'login_form.php'</script>";
}
?>
</body>
</html>
