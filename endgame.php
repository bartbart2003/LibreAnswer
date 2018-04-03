<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
<title>LibreAnswer</title>
<?php include 'stats.php'; ?>
</head>
<body>
<?php
echo "<script>window.location.href = 'index.php'</script>";
echo 'Game ended. Redirecting to main page...<br>';
echo "If the automatic redirection isn't working, <a href='index.html' style='color: black'>click here to go back to the main page.</a>";
?>
</body>
</html>
