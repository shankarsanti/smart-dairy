<?php require_once 'session.php'; requireLogin(); $user = getCurrentUser();

$demoData = false;
$collections = [
    ['id' => 1, 'date' => '2025-06-21', 'farmer' => 'Ramesh Patel', 'liters' => 18.5, 'fat_percent' => 4.2, 'quality' => 'Good'],
    ['id' => 2, 'date' => '2025-06-21', 'farmer' => 'Sita Kumari', 'liters' => 14.8, 'fat_percent' => 3.8, 'quality' => 'Good'],
    ['id' => 3, 'date' => '2025-06-20', 'farmer' => 'Mahesh Yadav', 'liters' => 16.2, 'fat_percent' => 4.0, 'quality' => 'Excellent'],
];
$demoData = true;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Collection - Dairy System</title>
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
            <div class="topbar"><h2 class="page-title"><i class="fas fa-tint"></i> Milk Collection</h2><div style="text-align: right;"><strong><?php echo htmlspecialchars($user['name']); ?></strong></div></div>
            <?php if ($demoData): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>This is sample milk collection data to preview the module. Record real collections to replace it.</span>
                </div>
            <?php endif; ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Farmer Name</th>
                            <th>Liters</th>
                            <th>Fat %</th>
                            <th>Quality</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collections as $col): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($col['id']); ?></td>
                                <td><?php echo htmlspecialchars($col['date']); ?></td>
                                <td><?php echo htmlspecialchars($col['farmer']); ?></td>
                                <td><?php echo htmlspecialchars($col['liters']); ?></td>
                                <td><?php echo htmlspecialchars($col['fat_percent']); ?></td>
                                <td><span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px;"><?php echo htmlspecialchars($col['quality']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
