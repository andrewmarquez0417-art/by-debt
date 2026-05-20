<?php include 'db.php';
$id = $_GET['id'];
$debt = $conn->query("SELECT * FROM debts WHERE id=$id")->fetch_assoc();
$payments = $conn->query("SELECT * FROM debt_payments WHERE debt_id=$id ORDER BY payment_month ASC");
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= $debt['name'] ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5">
    <div class="container">
        <h2><?= $debt['name'] ?> – ₱<?= number_format($debt['amount'], 2) ?></h2>
        <p><strong>Total Months:</strong> <?= $debt['total_months'] ?></p>
        <p><strong>Start Date:</strong> <?= $debt['start_date'] ?></p>
        <a href="index.php" class="btn btn-secondary mb-3">← Back</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $payments->fetch_assoc()): ?>
                    <tr>
                        <td>Month <?= $p['payment_month'] ?></td>
                        <td><?= $p['is_paid'] ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-danger">Unpaid</span>' ?></td>
                        <td>
                            <?php if (!$p['is_paid']): ?>
                                <a href="mark_paid.php?id=<?= $p['id'] ?>&debt_id=<?= $id ?>" class="btn btn-sm btn-success">Mark as Paid</a>
                            <?php else: ?>
                                <span class="text-muted">✓</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>