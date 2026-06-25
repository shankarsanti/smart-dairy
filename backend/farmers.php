<?php
require_once 'session.php';
requireLogin();

$user = getCurrentUser();
$action = $_GET['action'] ?? 'list';
$farmers = [];
$villages = [];
$message = '';
$error = '';
$demoData = false;

// Get all villages for dropdown (correct column: vname)
$villages = getRows("SELECT vid as village_id, vname as village_name FROM village ORDER BY vname");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $fname = sanitize($_POST['fname'] ?? '');
        $ph = sanitize($_POST['ph'] ?? '');
        $f_vid = sanitize($_POST['f_vid'] ?? '');
        $milk_type = sanitize($_POST['milk_type'] ?? '');
        $min_litre = (int)($_POST['min_litre'] ?? 0);
        
        if ($fname && $ph && $f_vid) {
            $query = "INSERT INTO farmer (fname, ph, f_vid, milk_type, min_litre) 
                     VALUES ('$fname', '$ph', '$f_vid', '$milk_type', $min_litre)";
            $result = executeUpdate($query);
            if ($result['success']) {
                $message = 'Farmer added successfully!';
                $action = 'list';
            } else {
                $error = 'Error adding farmer: ' . $result['error'];
            }
        } else {
            $error = 'Please fill all required fields';
        }
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $fname = sanitize($_POST['fname'] ?? '');
        $ph = sanitize($_POST['ph'] ?? '');
        $f_vid = sanitize($_POST['f_vid'] ?? '');
        $milk_type = sanitize($_POST['milk_type'] ?? '');
        $min_litre = (int)($_POST['min_litre'] ?? 0);
        
        if ($id && $fname && $ph) {
            $query = "UPDATE farmer SET fname='$fname', ph='$ph', f_vid='$f_vid', 
                     milk_type='$milk_type', min_litre=$min_litre WHERE id=$id";
            $result = executeUpdate($query);
            if ($result['success']) {
                $message = 'Farmer updated successfully!';
                $action = 'list';
            } else {
                $error = 'Error updating farmer: ' . $result['error'];
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $query = "DELETE FROM farmer WHERE id=$id";
            $result = executeUpdate($query);
            if ($result['success']) {
                $message = 'Farmer deleted successfully!';
                $action = 'list';
            } else {
                $error = 'Error deleting farmer: ' . $result['error'];
            }
        }
    }
}

// Get farmer data based on action
if ($action === 'list') {
    $search = sanitize($_GET['search'] ?? '');
    if ($search) {
        $query = "SELECT f.*, v.vname as village_name FROM farmer f 
                  LEFT JOIN village v ON f.f_vid = v.vid 
                  WHERE f.fname LIKE '%$search%' OR f.ph LIKE '%$search%' 
                  ORDER BY f.fname";
    } else {
        $query = "SELECT f.*, v.vname as village_name FROM farmer f 
                  LEFT JOIN village v ON f.f_vid = v.vid 
                  ORDER BY f.fname";
    }
    $farmers = getRows($query);
    if (empty($farmers) && empty($search)) {
        $demoFarmers = [
            ['id' => 101, 'fname' => 'Ramesh Patel', 'ph' => '9876543210', 'village_name' => 'Shivpura', 'milk_type' => 'cow', 'min_litre' => '12.5'],
            ['id' => 102, 'fname' => 'Sita Kumari', 'ph' => '9123456780', 'village_name' => 'Dhara', 'milk_type' => 'buffalo', 'min_litre' => '9.8'],
            ['id' => 103, 'fname' => 'Mahesh Yadav', 'ph' => '9988776655', 'village_name' => 'Bharatpur', 'milk_type' => 'both', 'min_litre' => '16.2'],
        ];
        $farmers = $demoFarmers;
        $demoData = true;
    }
} elseif ($action === 'edit' || $action === 'view') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        $farmer = getRow("SELECT f.*, v.vname as village_name FROM farmer f 
                         LEFT JOIN village v ON f.f_vid = v.vid 
                         WHERE f.id=$id");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Management - Dairy System</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.css">
    <link rel="stylesheet" href="../frontend/css/all.css">
    <link rel="stylesheet" href="../frontend/css/admin.css">
    <style>
        .back-link {
            color: #667eea;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .form-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 1.25rem;
            box-shadow: 0 24px 45px rgba(15, 23, 42, 0.08);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 700;
            color: #334155;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.95rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.85rem;
            background: #f8fafc;
            color: #0f172a;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.12);
        }

        .form-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-edit,
        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1rem;
            border-radius: 0.95rem;
            border: none;
            font-weight: 700;
            color: #ffffff;
            cursor: pointer;
        }

        .btn-edit {
            background: #0ea5e9;
        }

        .btn-delete {
            background: #ef4444;
        }

        .table-container {
            background: #ffffff;
            border-radius: 1.15rem;
            box-shadow: 0 24px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .table-controls {
            padding: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .search-box {
            flex: 1;
            min-width: 220px;
        }

        .search-box input {
            width: 100%;
            padding: 0.95rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.85rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            font-weight: 700;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }

        tr:hover {
            background: rgba(34, 197, 94, 0.05);
        }

        @media (max-width: 768px) {
            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-cow"></i> Pooja's Dairy</h3>
            </div>
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
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="page-title" style="display: inline-flex; align-items: center; gap: 0.75rem; white-space: nowrap; margin: 0;"><i class="fas fa-users"></i> Farmer Management</h2>
                <div style="text-align: right;"><strong><?php echo htmlspecialchars($user['name']); ?></strong></div>
            </div>
            
            <!-- Messages -->
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($message); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($demoData): ?>
                <div class="alert alert-info" style="background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8;">
                    <i class="fas fa-info-circle"></i>
                    <span>This is sample farmer data to preview the module. Add real farmers to replace it.</span>
                </div>
            <?php endif; ?>
            
            <?php if ($action === 'list'): ?>
                <!-- List View -->
                <div class="table-container">
                    <div class="table-controls">
                        <div class="search-box">
                            <form method="GET" style="display: flex;">
                                <input type="text" name="search" placeholder="Search farmers by name or phone..." 
                                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Search</button>
                            </form>
                        </div>
                        <div class="add-btn-container">
                            <a href="?action=add" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Farmer
                            </a>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Village</th>
                                <th>Milk Type</th>
                                <th>Min Liters</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($farmers)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                                        No farmers found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($farmers as $farmer): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($farmer['id']); ?></td>
                                        <td><?php echo htmlspecialchars($farmer['fname']); ?></td>
                                        <td><?php echo htmlspecialchars($farmer['ph']); ?></td>
                                        <td><?php echo htmlspecialchars($farmer['village_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($farmer['milk_type']); ?></td>
                                        <td><?php echo htmlspecialchars($farmer['min_litre']); ?></td>
                                        <td>
                                            <a href="?action=view&id=<?php echo $farmer['id']; ?>" class="btn btn-edit">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="?action=edit&id=<?php echo $farmer['id']; ?>" class="btn btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this farmer?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $farmer['id']; ?>">
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            
            <?php elseif ($action === 'add'): ?>
                <!-- Add Form -->
                <a href="?action=list" class="back-link"><i class="fas fa-arrow-left"></i> Back to List</a>
                <div class="form-container">
                    <h3><i class="fas fa-plus-circle"></i> Add New Farmer</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="fname">Farmer Name *</label>
                            <input type="text" id="fname" name="fname" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ph">Phone Number *</label>
                            <input type="text" id="ph" name="ph" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="f_vid">Village</label>
                            <select id="f_vid" name="f_vid">
                                <option value="">Select Village</option>
                                <?php foreach ($villages as $village): ?>
                                    <option value="<?php echo $village['village_id']; ?>">
                                        <?php echo htmlspecialchars($village['village_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="milk_type">Milk Type</label>
                            <select id="milk_type" name="milk_type">
                                <option value="">Select Milk Type</option>
                                <option value="cow">Cow Milk</option>
                                <option value="buffalo">Buffalo Milk</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="min_litre">Minimum Liters per Day</label>
                            <input type="number" id="min_litre" name="min_litre" min="0" step="0.1">
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Add Farmer
                            </button>
                            <a href="?action=list" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            
            <?php elseif ($action === 'edit' && isset($farmer)): ?>
                <!-- Edit Form -->
                <a href="?action=list" class="back-link"><i class="fas fa-arrow-left"></i> Back to List</a>
                <div class="form-container">
                    <h3><i class="fas fa-edit"></i> Edit Farmer</h3>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $farmer['id']; ?>">
                        
                        <div class="form-group">
                            <label for="fname">Farmer Name *</label>
                            <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($farmer['fname']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ph">Phone Number *</label>
                            <input type="text" id="ph" name="ph" value="<?php echo htmlspecialchars($farmer['ph']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="f_vid">Village</label>
                            <select id="f_vid" name="f_vid">
                                <option value="">Select Village</option>
                                <?php foreach ($villages as $village): ?>
                                    <option value="<?php echo $village['village_id']; ?>"
                                        <?php echo $village['village_id'] == $farmer['f_vid'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($village['village_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="milk_type">Milk Type</label>
                            <select id="milk_type" name="milk_type">
                                <option value="">Select Milk Type</option>
                                <option value="cow" <?php echo $farmer['milk_type'] === 'cow' ? 'selected' : ''; ?>>Cow Milk</option>
                                <option value="buffalo" <?php echo $farmer['milk_type'] === 'buffalo' ? 'selected' : ''; ?>>Buffalo Milk</option>
                                <option value="both" <?php echo $farmer['milk_type'] === 'both' ? 'selected' : ''; ?>>Both</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="min_litre">Minimum Liters per Day</label>
                            <input type="number" id="min_litre" name="min_litre" value="<?php echo $farmer['min_litre']; ?>" min="0" step="0.1">
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Farmer
                            </button>
                            <a href="?action=list" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            
            <?php elseif ($action === 'view' && isset($farmer)): ?>
                <!-- View Details -->
                <a href="?action=list" class="back-link"><i class="fas fa-arrow-left"></i> Back to List</a>
                <div class="form-container">
                    <h3><i class="fas fa-user-circle"></i> Farmer Details</h3>
                    
                    <div class="form-group">
                        <label>Farmer ID</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo htmlspecialchars($farmer['id']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Name</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo htmlspecialchars($farmer['fname']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo htmlspecialchars($farmer['ph']); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Village</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo htmlspecialchars($farmer['village_name'] ?? 'N/A'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Milk Type</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo htmlspecialchars(ucfirst($farmer['milk_type'] ?? 'N/A')); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Minimum Liters per Day</label>
                        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php echo htmlspecialchars($farmer['min_litre'] ?? '0'); ?> L
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <a href="?action=edit&id=<?php echo $farmer['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Farmer
                        </a>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
