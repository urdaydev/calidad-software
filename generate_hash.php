<?php
require_once("libraries/password_compatibility_library.php");
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash para admin123: " . $hash;
