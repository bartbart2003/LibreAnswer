<?php
session_start();
session_destroy();
echo "<script>window.location.href = 'index.php'</script>";
echo 'Game ended. Redirecting to main page...<br>';
echo "If the automatic redirection isn't working, <a href='index.html' style='color: black'>click here to go back to the main page.</a>";
?>
