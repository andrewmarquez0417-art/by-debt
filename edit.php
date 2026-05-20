<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];

    $conn->query("UPDATE debts SET name='$name', amount='$amount' WHERE id=$id");
    header("Location: index.php");
}
