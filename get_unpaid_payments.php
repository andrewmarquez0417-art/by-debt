<?php
include 'db.php';

$debt_id = $_GET['debt_id'];

$payment = $conn->query("SELECT id FROM debt_payments WHERE debt_id = $debt_id AND is_paid = 0 ORDER BY payment_month ASC LIMIT 1");
$paymentIds = [];
if ($payment->num_rows > 0) {
    $row = $payment->fetch_assoc();
    $paymentIds[] = $row['id'];
}
echo json_encode($paymentIds);
