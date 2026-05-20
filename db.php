<?php
$host = 'sql110.infinityfree.com';
$user = 'if0_39405913';
$pass = 'YyTX0RVspG';
$dbname = 'if0_39405913_debt_manager';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
