<?php
// Replace 'your_password' with the desired password
$password = 'andrew17';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Output the hashed password
echo "Hashed Password: " . $hashedPassword;
