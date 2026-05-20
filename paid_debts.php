<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize date range variables
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the SQL query for paid payments
$sql = "SELECT dp.id, dp.debt_id, dp.payment_month, dp.payment_date, d.name, d.amount, d.total_months, d.payment_day
        FROM debt_payments dp
        JOIN debts d ON dp.debt_id = d.id
        WHERE dp.is_paid = 1";
if ($start_date && $end_date) {
    $sql .= " AND dp.payment_date BETWEEN ? AND ?";
}
$stmt = $conn->prepare($sql);
if ($start_date && $end_date) {
    $stmt->bind_param("ss", $start_date, $end_date);
}
$stmt->execute();
$result = $stmt->get_result();

// Initialize total paid amount
$total_paid = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debt Manager - Paid Debts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="header d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-credit-card"></i> My Debt Manager</h1>
            <div>
                <a href="index.php" class="btn btn-primary me-2"><i class="bi bi-house"></i> Home</a>
                <a href="logout.php" class="btn btn-secondary"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-check-circle"></i> Paid Debts</h5>
            </div>
            <div class="card-body">
                <!-- Date Range Filter -->
                <form method="get" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                            <a href="paid_debts.php" class="btn btn-secondary"><i class="bi bi-arrow-repeat"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Paid Debts Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Debt Name</th>
                                <th>Amount</th>
                                <th>Monthly Payment</th>
                                <th>Payment Month</th>
                                <th>Payment Day</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <?php
                                    // Calculate monthly payment and add to total
                                    $monthly_payment = $row['amount'] / $row['total_months'];
                                    $total_paid += $monthly_payment;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td>₱<?= number_format($row['amount'], 2) ?></td>
                                        <td>₱<?= number_format($monthly_payment, 2) ?></td>
                                        <td>Month <?= $row['payment_month'] ?></td>
                                        <td><?= $row['payment_day'] ?></td>
                                        <td><?= $row['payment_date'] ? date('Y-m-d', strtotime($row['payment_date'])) : 'N/A' ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No paid debts found<?php if ($start_date && $end_date) echo " for the selected date range"; ?>.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Display Total Paid Amount -->
                <?php if ($result->num_rows > 0): ?>
                    <div class="mt-3 text-end">
                        <h5>Total Paid: ₱<?= number_format($total_paid, 2) ?></h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>