<?php
require_once 'session.php';
requireLogin();

$stats = getDashboardStats();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dairy Management System</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.css">
    <link rel="stylesheet" href="../frontend/css/all.css">
    <link rel="stylesheet" href="../frontend/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.14), transparent 28%),
                        linear-gradient(180deg, #e7f0ff 0%, #f8fbff 34%, #eef4fb 100%);
            color: #1f2937;
        }
        
        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #0b1120 100%);
            color: #e2e8f0;
            min-height: 100vh;
            position: fixed;
            width: 280px;
            left: 0;
            top: 0;
            overflow-y: auto;
            padding-top: 24px;
            z-index: 100;
            border-right: 1px solid rgba(148, 163, 184, 0.16);
        }
        
        .sidebar-header {
            padding: 24px 24px 18px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.16);
            margin-bottom: 24px;
        }
        
        .sidebar-header h3 {
            font-size: 22px;
            margin: 0;
            letter-spacing: 0.03em;
            color: #ffffff;
        }
        
        .nav-section {
            margin-bottom: 24px;
        }
        
        .nav-section-title {
            padding: 0 24px 12px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            opacity: 0.62;
            letter-spacing: 0.12em;
            color: #94a3b8;
        }
        
        .nav-item {
            display: block;
            padding: 14px 24px;
            color: #e2e8f0;
            text-decoration: none;
            transition: all 0.25s ease;
            border-left: 4px solid transparent;
            border-radius: 0 24px 24px 0;
        }
        
        .nav-item:hover,
        .nav-item.active {
            background: rgba(255,255,255,0.05);
            border-left-color: #22c55e;
            color: white;
        }
        
        .nav-item i {
            margin-right: 14px;
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 0;
        }
        
        .topbar {
            background: #ffffff;
            padding: 24px 32px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .topbar-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .user-info {
            text-align: right;
            min-width: 160px;
        }
        
        .user-name {
            font-weight: 700;
            color: #0f172a;
        }
        
        .user-role {
            font-size: 0.85rem;
            color: #64748b;
        }
        
        .logout-btn {
            background: #22c55e;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            letter-spacing: 0.01em;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(34, 197, 94, 0.25);
        }
        
        .page-content {
            padding: 32px 40px 40px;
        }
        
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 36px;
        }
        
        .stat-card {
            background: #ffffff;
            padding: 24px;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            gap: 18px;
            border: 1px solid rgba(148, 163, 184, 0.12);
        }
        
        .stat-icon {
            font-size: 32px;
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: white;
        }
        
        .stat-icon.blue {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        }
        
        .stat-icon.green {
            background: linear-gradient(135deg, #16a34a 0%, #86efac 100%);
            color: #0f172a;
        }
        
        .stat-icon.orange {
            background: linear-gradient(135deg, #f97316 0%, #fbbf24 100%);
        }
        
        .stat-icon.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
        }
        
        .stat-info h4 {
            margin: 0;
            color: #334155;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin: 8px 0 0 0;
        }
        
        /* Modules Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 28px;
        }
        
        .module-card {
            background: #ffffff;
            padding: 26px 20px;
            border-radius: 24px;
            text-align: center;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
            text-decoration: none;
            color: #0f172a;
            border: 1px solid transparent;
        }
        
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 55px rgba(15, 23, 42, 0.09);
            border-color: rgba(34, 197, 94, 0.24);
        }
        
        .module-icon {
            font-size: 46px;
            margin-bottom: 14px;
            background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .module-name {
            font-weight: 800;
            font-size: 0.98rem;
            letter-spacing: 0.01em;
        }
        
        .module-card span {
            color: #64748b;
            display: block;
            margin-top: 6px;
            font-size: 0.88rem;
        }
        
        /* Responsive */
        @media (max-width: 960px) {
            .sidebar {
                width: 80px;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .nav-item span {
                display: none;
            }
            
            .nav-section-title {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-content {
                padding: 24px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-cow"></i> Pooja's Dairy</h3>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            <a href="dashboard.php" class="nav-item">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Management</div>
            <a href="farmers.php" class="nav-item">
                <i class="fas fa-users"></i> <span>Farmers</span>
            </a>
            <a href="animals.php" class="nav-item">
                <i class="fas fa-cow"></i> <span>Animals</span>
            </a>
            <a href="milk-collection.php" class="nav-item">
                <i class="fas fa-tint"></i> <span>Milk Collection</span>
            </a>
            <a href="rates.php" class="nav-item">
                <i class="fas fa-percentage"></i> <span>Rates & Settings</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Billing</div>
            <a href="billing.php" class="nav-item">
                <i class="fas fa-receipt"></i> <span>Billing</span>
            </a>
            <a href="bill-history.php" class="nav-item">
                <i class="fas fa-history"></i> <span>Bill History</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Reports</div>
            <a href="reports.php" class="nav-item">
                <i class="fas fa-chart-bar"></i> <span>Reports</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">System</div>
            <a href="settings.php" class="nav-item">
                <i class="fas fa-cog"></i> <span>Settings</span>
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2 class="topbar-title">
                <i class="fas fa-chart-line"></i> Dashboard
            </h2>
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="user-role"><?php echo ucfirst($user['role']); ?></div>
                </div>
                <img src="../frontend/images/mainimg.jpeg" alt="<?php echo htmlspecialchars($user['name']); ?>" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="page-content">
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Total Farmers</h4>
                        <p class="stat-value"><?php echo $stats['total_farmers']; ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Today's Milk (Liters)</h4>
                        <p class="stat-value"><?php echo round($stats['today_milk'], 1); ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Today's Revenue</h4>
                        <p class="stat-value">₹<?php echo number_format($stats['today_revenue'], 0); ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Monthly Collection</h4>
                        <p class="stat-value"><?php echo round($stats['monthly_milk'], 1); ?>L</p>
                    </div>
                </div>
            </div>
            
            <!-- Modules -->
            <h3 style="margin-top: 40px; margin-bottom: 20px; color: #333;">
                <i class="fas fa-th"></i> Quick Access
            </h3>
            <div class="modules-grid">
                <a href="farmers.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-users"></i></div>
                    <div class="module-name">Farmer Management</div>
                </a>
                
                <a href="animals.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-cow"></i></div>
                    <div class="module-name">Animal Management</div>
                </a>
                
                <a href="milk-collection.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-tint"></i></div>
                    <div class="module-name">Milk Collection</div>
                </a>
                
                <a href="rates.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-percentage"></i></div>
                    <div class="module-name">Rates & SNF</div>
                </a>
                
                <a href="billing.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-receipt"></i></div>
                    <div class="module-name">Generate Bills</div>
                </a>
                
                <a href="bill-history.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-history"></i></div>
                    <div class="module-name">Bill History</div>
                </a>
                
                <a href="reports.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-chart-bar"></i></div>
                    <div class="module-name">Reports</div>
                </a>
                
                <a href="settings.php" class="module-card">
                    <div class="module-icon"><i class="fas fa-cog"></i></div>
                    <div class="module-name">Settings</div>
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
