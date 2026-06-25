<?php require_once 'session.php'; requireLogin(); $user = getCurrentUser();

$demoData = false;
$history = [
    ['id' => 1, 'bill_no' => 'BL-2025-001', 'farmer' => 'Ramesh Patel', 'period' => 'May 2025', 'amount' => 8125.00, 'paid_date' => '2025-06-05', 'payment_method' => 'Bank Transfer'],
    ['id' => 2, 'bill_no' => 'BL-2025-002', 'farmer' => 'Sita Kumari', 'period' => 'May 2025', 'amount' => 7920.00, 'paid_date' => '2025-06-06', 'payment_method' => 'Cash'],
    ['id' => 3, 'bill_no' => 'BL-2025-003', 'farmer' => 'Mahesh Yadav', 'period' => 'May 2025', 'amount' => 7560.00, 'paid_date' => '2025-06-07', 'payment_method' => 'Bank Transfer'],
];
$demoData = true;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill History - Dairy System</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.css">
    <link rel="stylesheet" href="../frontend/css/all.css">
    <link rel="stylesheet" href="../frontend/css/admin.css">
    <style>
        .table-container { background: #fff; border-radius: 1.2rem; padding: 2rem; box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06); }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 2rem; margin-bottom: 1.5rem; }
        .page-title { display: inline-flex; align-items: center; gap: 0.75rem; white-space: nowrap; margin: 0; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f8fbff; }
        th { padding: 14px; text-align: left; font-weight: 700; color: #0f172a; border-bottom: 2px solid #e2e8f0; }
        td { padding: 14px; border-bottom: 1px solid #e2e8f0; color: #0f172a; }
        .btn { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; display: inline-block; }
        .btn-primary { background: #22c55e; color: #fff; }
        .alert { padding: 14px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="sidebar">
            <div class="sidebar-header"><h3><i class="fas fa-cow"></i> Pooja's Dairy</h3></div>
            <a href="dashboard.php" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
            <a href="farmers.php" class="nav-item"><i class="fas fa-users"></i> Farmers</a>
            <a href="animals.php" class="nav-item"><i class="fas fa-cow"></i> Animals</a>
            <a href="milk-collection.php" class="nav-item"><i class="fas fa-tint"></i> Milk Collection</a>
            <a href="rates.php" class="nav-item"><i class="fas fa-percentage"></i> Rates</a>
            <a href="billing.php" class="nav-item"><i class="fas fa-receipt"></i> Billing</a>
            <a href="bill-history.php" class="nav-item"><i class="fas fa-history"></i> Bill History</a>
            <a href="reports.php" class="nav-item"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="main-content">
            <div class="topbar"><h2 class="page-title"><i class="fas fa-history"></i> Bill History</h2><div style="text-align: right;"><strong><?php echo htmlspecialchars($user['name']); ?></strong></div></div>
            <?php if ($demoData): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>This is sample bill history data to preview the module. Process real bills to replace it.</span>
                </div>
            <?php endif; ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bill No</th>
                            <th>Farmer Name</th>
                            <th>Period</th>
                            <th>Amount (₹)</th>
                            <th>Paid Date</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $bill): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bill['id']); ?></td>
                                <td><?php echo htmlspecialchars($bill['bill_no']); ?></td>
                                <td><?php echo htmlspecialchars($bill['farmer']); ?></td>
                                <td><?php echo htmlspecialchars($bill['period']); ?></td>
                                <td><?php echo htmlspecialchars($bill['amount']); ?></td>
                                <td><?php echo htmlspecialchars($bill['paid_date']); ?></td>
                                <td><?php echo htmlspecialchars($bill['payment_method']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
