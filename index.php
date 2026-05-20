<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debt Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="header d-flex justify-content-between align-items-center">
            <h1><i class="bi bi-credit-card"></i> My Debt Manager</h1>
            <div>
                <a href="paid_debts.php" class="btn btn-primary me-2"><i class="bi bi-check-circle"></i> Paid Debts</a>

                <a href="logout.php" class="btn btn-secondary"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="add-debt-container">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> Add New Debt
            </button>
        </div>

        <div class="filter-container">
            <div style="min-width: 200px;">
                <label for="paymentDayFilter" class="form-label">Filter by Payment Day</label>
                <select id="paymentDayFilter" class="form-select" onchange="filterDebts()">
                    <option value="">All Days</option>
                    <?php
                    $uniqueDays = $conn->query("SELECT DISTINCT payment_day FROM debts ORDER BY payment_day");
                    while ($day = $uniqueDays->fetch_assoc()) {
                        echo "<option value='{$day['payment_day']}'>{$day['payment_day']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="total-display" id="totalMonthlyPayment">
                <i class="bi bi-calculator"></i> Total Monthly Payment: ₱0.00
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="debtTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab">
                    <i class="bi bi-hourglass-split"></i> Ongoing Debts
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                    <i class="bi bi-check-circle"></i> Completed Debts
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content pt-3" id="debtTabContent">
            <!-- Ongoing Debts Tab -->
            <div class="tab-pane fade show active" id="ongoing" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Active Debts</span>
                        <button id="payAllShowing" class="btn btn-success btn-sm" onclick="payAllShowing()">
                            <i class="bi bi-check2-all"></i> Pay All Showing
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="ongoingDebtTable">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" id="selectAllOngoing" onchange="toggleSelectAll('ongoing')"></th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Months</th>
                                    <th>Monthly</th>
                                    <th>Day</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $debts = $conn->query("SELECT * FROM debts");
                                while ($debt = $debts->fetch_assoc()):
                                    $id = $debt['id'];
                                    $total_payments = $conn->query("SELECT COUNT(*) AS c FROM debt_payments WHERE debt_id=$id")->fetch_assoc()['c'];
                                    $paid = $conn->query("SELECT COUNT(*) AS c FROM debt_payments WHERE debt_id=$id AND is_paid=1")->fetch_assoc()['c'];
                                    $monthly_payment = $debt['amount'] / $debt['total_months'];
                                    $progress = $total_payments > 0 ? ($paid / $total_payments) * 360 : 0; // Convert to degrees for circle

                                    if ($paid < $total_payments) { // Ongoing debt
                                ?>
                                        <tr data-payment-day="<?= $debt['payment_day'] ?>" data-monthly-payment="<?= number_format($monthly_payment, 2) ?>">
                                            <td><input type="checkbox" class="rowCheckbox" data-debt-id="<?= $id ?>" onchange="updateTotal()"></td>
                                            <td><?= $debt['name'] ?></td>
                                            <td>₱<?= number_format($debt['amount'], 2) ?></td>
                                            <td><?= $debt['total_months'] ?></td>
                                            <td>₱<?= number_format($monthly_payment, 2) ?></td>
                                            <td><?= $debt['payment_day'] ?></td>
                                            <td>
                                                <div class="progress-circle" style="background: conic-gradient(var(--primary-color) <?= $progress ?>deg, #e0e0e0 <?= $progress ?>deg 360deg);">
                                                    <span><?= $paid ?>/<?= $total_payments ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $id ?>">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $id ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $id ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                endwhile;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Completed Debts Tab -->
            <div class="tab-pane fade" id="completed" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Completed Debts
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="completedDebtTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Months</th>
                                    <th>Monthly</th>
                                    <th>Day</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $debts->data_seek(0);
                                while ($debt = $debts->fetch_assoc()):
                                    $id = $debt['id'];
                                    $total_payments = $conn->query("SELECT COUNT(*) AS c FROM debt_payments WHERE debt_id=$id")->fetch_assoc()['c'];
                                    $paid = $conn->query("SELECT COUNT(*) AS c FROM debt_payments WHERE debt_id=$id AND is_paid=1")->fetch_assoc()['c'];
                                    $monthly_payment = $debt['amount'] / $debt['total_months'];

                                    if ($paid >= $total_payments) { // Completed debt
                                ?>
                                        <tr data-payment-day="<?= $debt['payment_day'] ?>" data-monthly-payment="<?= number_format($monthly_payment, 2) ?>">
                                            <td><?= $debt['name'] ?></td>
                                            <td>₱<?= number_format($debt['amount'], 2) ?></td>
                                            <td><?= $debt['total_months'] ?></td>
                                            <td>₱<?= number_format($monthly_payment, 2) ?></td>
                                            <td><?= $debt['payment_day'] ?></td>
                                            <td>
                                                <div class="progress-circle" style="background: conic-gradient(var(--success-color) 360deg, #e0e0e0 0deg);">
                                                    <span><?= $paid ?>/<?= $total_payments ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $id ?>">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $id ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                endwhile;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Modals -->
        <?php
        $debts->data_seek(0);
        while ($debt = $debts->fetch_assoc()):
            $id = $debt['id'];
        ?>
            <div class="modal fade" id="viewModal<?= $id ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= $debt['name'] ?> – ₱<?= number_format($debt['amount'], 2) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Total Months</h6>
                                            <p class="card-text h5"><?= $debt['total_months'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Monthly Payment</h6>
                                            <p class="card-text h5">₱<?= number_format($debt['amount'] / $debt['total_months'], 2) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Payment Day</h6>
                                            <p class="card-text h5"><?= $debt['payment_day'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3">Payment History</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Due Date</th>
                                            <th>Date Paid</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $payments = $conn->query("SELECT * FROM debt_payments WHERE debt_id=$id ORDER BY payment_month ASC");
                                        if ($payments->num_rows > 0) {
                                            $start_date = date('Y-m-01', strtotime("2025-07-01 + " . ($debt['payment_day'] - 1) . " days"));
                                            while ($p = $payments->fetch_assoc()):
                                                $due_date = date('Y-m-d', strtotime("{$start_date} + " . ($p['payment_month'] - 1) . " months"));
                                                $due_date = date('Y-m-' . sprintf('%02d', $debt['payment_day']), strtotime($due_date));
                                                $payment_date = $p['payment_date'] ? date('Y-m-d', strtotime($p['payment_date'])) : 'N/A';
                                        ?>
                                                <tr>
                                                    <td>Month <?= $p['payment_month'] ?></td>
                                                    <td><?= $due_date ?></td>
                                                    <td><?= $payment_date ?></td>
                                                    <td>
                                                        <?= $p['is_paid'] ?
                                                            '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Paid</span>' :
                                                            '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Unpaid</span>' ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!$p['is_paid']): ?>
                                                            <a href="mark_paid.php?id=<?= $p['id'] ?>&debt_id=<?= $id ?>" class="btn btn-sm btn-success">
                                                                <i class="bi bi-check-lg"></i> Mark Paid
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted"><i class="bi bi-check2-all"></i></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            endwhile;
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center py-4'>No payment records found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $id ?>" tabindex="-1" aria-hidden="true">
                < pracy
                    <div class="modal-dialog">
                    <form method="post" action="edit.php">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Debt</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" class="form-control" value="<?= $debt['name'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input name="amount" class="form-control" type="number" step="0.01" value="<?= $debt['amount'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Day</label>
                                    <input name="payment_day" class="form-control" type="number" min="1" max="31" value="<?= $debt['payment_day'] ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
            </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal<?= $id ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong><?= $debt['name'] ?></strong>?</p>
                    <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="delete.php?id=<?= $id ?>" class="btn btn-danger">Delete Debt</a>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="add.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Debt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input name="amount" class="form-control" type="number" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Months</label>
                        <input name="months" class="form-control" type="number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Day</label>
                        <input name="payment_day" class="form-control" type="number" min="1" max="31" value="15" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Debt</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="get_unpaid_payments.php"></script>
<script src="mark_paid.php"></script>
<script>
    function toggleSelectAll(tab) {
        var selectAll = document.getElementById('selectAllOngoing');
        var checkboxes = document.querySelectorAll('#ongoingDebtTable .rowCheckbox');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].closest('tr').style.display !== 'none') {
                checkboxes[i].checked = selectAll.checked;
            }
        }
        updateTotal();
    }

    function updateTotal() {
        var checkboxes = document.querySelectorAll('#ongoingDebtTable .rowCheckbox');
        var total = 0;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked && checkboxes[i].closest('tr').style.display !== 'none') {
                var row = checkboxes[i].closest('tr');
                var monthlyPayment = parseFloat(row.getAttribute('data-monthly-payment').replace(',', ''));
                total += monthlyPayment;
            }
        }
        document.getElementById('totalMonthlyPayment').innerHTML =
            '<i class="bi bi-calculator"></i> Total Monthly Payment: ₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function filterDebts() {
        var paymentDay = document.getElementById('paymentDayFilter').value;
        var ongoingRows = document.querySelectorAll('#ongoingDebtTable tbody tr');
        var completedRows = document.querySelectorAll('#completedDebtTable tbody tr');

        ongoingRows.forEach(row => {
            var rowPaymentDay = row.getAttribute('data-payment-day');
            if (paymentDay === '' || rowPaymentDay === paymentDay) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
                var checkbox = row.querySelector('.rowCheckbox');
                if (checkbox) checkbox.checked = false;
            }
        });

        completedRows.forEach(row => {
            var rowPaymentDay = row.getAttribute('data-payment-day');
            if (paymentDay === '' || rowPaymentDay === paymentDay) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('selectAllOngoing').checked = false;
        updateTotal();
    }

    function payAllShowing() {
        var paymentDayFilter = document.getElementById('paymentDayFilter').value;
        var checkboxes = document.querySelectorAll('#ongoingDebtTable .rowCheckbox');
        var paymentIds = [];

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked && checkboxes[i].closest('tr').style.display !== 'none') {
                var row = checkboxes[i].closest('tr');
                var debtId = checkboxes[i].getAttribute('data-debt-id');
                var rowPaymentDay = row.getAttribute('data-payment-day');

                if (paymentDayFilter === '' || rowPaymentDay === paymentDayFilter) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'get_unpaid_payments.php?debt_id=' + debtId, false);
                    xhr.send();
                    if (xhr.status === 200) {
                        var paymentIdsResponse = JSON.parse(xhr.responseText);
                        if (paymentIdsResponse.length > 0) {
                            paymentIds.push(paymentIdsResponse[0]);
                        }
                    }
                }
            }
        }

        if (paymentIds.length > 0) {
            if (confirm(`Are you sure you want to mark ${paymentIds.length} payment(s) as paid?`)) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'mark_paid.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === 'success') {
                            alert('Payments marked as paid successfully!');
                            location.reload();
                        } else {
                            alert('Error marking payments as paid.');
                        }
                    }
                };
                xhr.send('ids=' + JSON.stringify(paymentIds));
            }
        } else {
            alert('No unpaid payments selected.');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>
</body>

</html>