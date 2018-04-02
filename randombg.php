<?php
// This file returns css with random background images from backgrounds/ folder
$files = glob("backgrounds/*.{jpg,png}", GLOB_BRACE);
$file = $files[array_rand($files, 1)];
echo "body { background-image: url('".$file."'); }";
?>
