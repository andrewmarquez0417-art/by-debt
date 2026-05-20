<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $months = $_POST['months'];
    $payment_day = $_POST['payment_day'];

    // Insert into debts table with payment_day instead of start_date
    $conn->query("INSERT INTO debts (name, amount, total_months, payment_day) VALUES ('$name', '$amount', '$months', '$payment_day')");
    $debt_id = $conn->insert_id;

    // Insert payment records for each month
    for ($i = 1; $i <= $months; $i++) {
        $conn->query("INSERT INTO debt_payments (debt_id, payment_month) VALUES ($debt_id, $i)");
    }

    header("Location: index.php");
}
