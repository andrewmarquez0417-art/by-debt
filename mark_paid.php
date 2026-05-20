<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request from payAllShowing (multiple payment IDs)
    if (isset($_POST['ids'])) {
        $ids = json_decode($_POST['ids'], true);
        if (is_array($ids) && !empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $conn->prepare("UPDATE debt_payments SET is_paid = 1, payment_date = CURDATE() WHERE id IN ($placeholders)");
            if ($stmt === false) {
                die('Prepare failed: ' . $conn->error);
            }
            $types = str_repeat('i', count($ids));
            $stmt->bind_param($types, ...$ids);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'error: Invalid or empty payment IDs';
        }
    } else {
        echo 'error: No payment IDs provided';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request from "Mark as Paid" link (single payment ID)
    if (isset($_GET['id']) && isset($_GET['debt_id'])) {
        $payment_id = intval($_GET['id']);
        $debt_id = intval($_GET['debt_id']);

        // Verify the payment ID belongs to the debt_id
        $stmt = $conn->prepare("UPDATE debt_payments SET is_paid = 1, payment_date = CURDATE() WHERE id = ? AND debt_id = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param('ii', $payment_id, $debt_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Redirect back to the main page with the debt_id to refresh the view modal
                header("Location: index.php#viewModal$debt_id");
                exit;
            } else {
                die('Error: No payment found with the specified ID or debt_id');
            }
        } else {
            die('Error: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        die('Error: Missing id or debt_id parameter');
    }
} else {
    die('Error: Invalid request method');
}

$conn->close();
