<?php require_once 'session.php'; requireLogin(); $user = getCurrentUser();

$demoData = false;
$billings = [
    ['id' => 1, 'bill_no' => 'BL-2025-001', 'farmer' => 'Ramesh Patel', 'period' => 'June 2025', 'liters' => 185.5, 'amount' => 8347.50, 'status' => 'Pending'],
    ['id' => 2, 'bill_no' => 'BL-2025-002', 'farmer' => 'Sita Kumari', 'period' => 'June 2025', 'liters' => 148.8, 'amount' => 8184.00, 'status' => 'Pending'],
    ['id' => 3, 'bill_no' => 'BL-2025-003', 'farmer' => 'Mahesh Yadav', 'period' => 'June 2025', 'liters' => 162.2, 'amount' => 7785.60, 'status' => 'Paid'],
];
$demoData = true;
$dairyName = "Pooja's Dairy";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing - Dairy System</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.css">
    <link rel="stylesheet" href="../frontend/css/all.css">
    <link rel="stylesheet" href="../frontend/css/admin.css">
    <style>
        .table-container { background: #fff; border-radius: 1.2rem; padding: 2rem; box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06); margin-bottom: 2rem; }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 2rem; margin-bottom: 1.5rem; }
        .page-title { display: inline-flex; align-items: center; gap: 0.75rem; white-space: nowrap; margin: 0; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f8fbff; }
        th { padding: 14px; text-align: left; font-weight: 700; color: #0f172a; border-bottom: 2px solid #e2e8f0; }
        td { padding: 14px; border-bottom: 1px solid #e2e8f0; color: #0f172a; }
        .btn { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; display: inline-block; cursor: pointer; border: none; }
        .btn-primary { background: #22c55e; color: #fff; }
        .btn-print { background: #3b82f6; color: #fff; margin-left: 6px; }
        .btn-print:hover { background: #2563eb; }
        .alert { padding: 14px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .modal-content h3 { margin-top: 0; color: #0f172a; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #0f172a; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); }
        .close { color: #999; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: #000; }
        .print-bill { display: none; }
        @media print {
            body { background: white; }
            .sidebar, .topbar, .table-controls, .btn, .alert { display: none !important; }
            .print-bill { display: block !important; }
            .table-container { box-shadow: none; page-break-inside: avoid; }
        }
        .bill-header { text-align: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #0f172a; }
        .bill-header h1 { margin: 0; font-size: 2rem; color: #0f172a; }
        .bill-header p { margin: 0.5rem 0; color: #64748b; }
        .bill-details { margin-bottom: 2rem; }
        .bill-details-row { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
        .bill-details-row strong { color: #0f172a; }
        .bill-items { width: 100%; border-collapse: collapse; margin: 2rem 0; }
        .bill-items th { background: #f1f5f9; padding: 12px; text-align: left; border-bottom: 2px solid #cbd5e1; }
        .bill-items td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
        .bill-total { text-align: right; margin: 2rem 0; padding: 1rem; background: #f8fbff; border-radius: 6px; }
        .bill-total h3 { margin: 0; color: #0f172a; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="sidebar">
            <div class="sidebar-header"><h3><i class="fas fa-cow"></i> <?php echo htmlspecialchars($dairyName); ?></h3></div>
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
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <h2 class="page-title"><i class="fas fa-receipt"></i> Billing</h2>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                </div>
            </div>
            <?php if ($demoData): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>This is sample billing data to preview the module. Create real bills to replace it.</span>
                </div>
            <?php endif; ?>
            
            <div class="table-container">
                <div class="table-controls">
                    <button class="btn btn-primary" onclick="document.getElementById('newBillModal').style.display='block'">
                        <i class="fas fa-plus"></i> New Bill
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bill No</th>
                            <th>Farmer Name</th>
                            <th>Period</th>
                            <th>Liters</th>
                            <th>Amount (₹)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($billings as $bill): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bill['id']); ?></td>
                                <td><?php echo htmlspecialchars($bill['bill_no']); ?></td>
                                <td><?php echo htmlspecialchars($bill['farmer']); ?></td>
                                <td><?php echo htmlspecialchars($bill['period']); ?></td>
                                <td><?php echo htmlspecialchars($bill['liters']); ?></td>
                                <td><?php echo htmlspecialchars($bill['amount']); ?></td>
                                <td><span class="status <?php echo 'status-' . strtolower($bill['status']); ?>"><?php echo htmlspecialchars($bill['status']); ?></span></td>
                                <td>
                                    <button class="btn btn-print" onclick="printBill(<?php echo htmlspecialchars(json_encode($bill)); ?>, '<?php echo htmlspecialchars($dairyName); ?>')">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- New Bill Modal -->
    <div id="newBillModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('newBillModal').style.display='none'">&times;</span>
            <h3><i class="fas fa-plus"></i> Create New Bill</h3>
            <form onsubmit="handleNewBill(event)">
                <div class="form-group">
                    <label>Farmer Name *</label>
                    <input type="text" id="farmerName" required placeholder="Enter farmer name">
                </div>
                <div class="form-group">
                    <label>Period *</label>
                    <input type="text" id="billPeriod" required placeholder="e.g., June 2025">
                </div>
                <div class="form-group">
                    <label>Liters *</label>
                    <input type="number" id="billLiters" required placeholder="Enter liters" step="0.1">
                </div>
                <div class="form-group">
                    <label>Amount (₹) *</label>
                    <input type="number" id="billAmount" required placeholder="Enter amount" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; cursor: pointer;">
                    <i class="fas fa-save"></i> Create Bill
                </button>
            </form>
        </div>
    </div>
    
    <!-- Print Bill View (Hidden) -->
    <div id="printBillView" class="print-bill"></div>
    
    <script>
        function printBill(bill, dairyName) {
            const billHTML = `
                <div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 2rem; background: white;">
                    <div class="bill-header">
                        <h1>📋 BILL STATEMENT</h1>
                        <p style="font-size: 18px; font-weight: bold; color: #22c55e;">${dairyName}</p>
                        <p>Dairy Management System</p>
                    </div>
                    
                    <div class="bill-details">
                        <div class="bill-details-row">
                            <span><strong>Bill Number:</strong></span>
                            <span>${bill.bill_no}</span>
                        </div>
                        <div class="bill-details-row">
                            <span><strong>Farmer Name:</strong></span>
                            <span>${bill.farmer}</span>
                        </div>
                        <div class="bill-details-row">
                            <span><strong>Period:</strong></span>
                            <span>${bill.period}</span>
                        </div>
                        <div class="bill-details-row">
                            <span><strong>Bill Date:</strong></span>
                            <span>${new Date().toLocaleDateString()}</span>
                        </div>
                    </div>
                    
                    <table class="bill-items">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Milk Collection - ${bill.period}</td>
                                <td>${bill.liters} Liters</td>
                                <td>₹${(bill.amount / bill.liters).toFixed(2)}/L</td>
                                <td>₹${bill.amount.toFixed(2)}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="bill-total">
                        <h3>Total Amount: ₹${bill.amount.toFixed(2)}</h3>
                        <p style="margin: 0.5rem 0; color: #64748b;">Status: ${bill.status}</p>
                    </div>
                    
                    <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #cbd5e1; text-align: center; color: #64748b; font-size: 12px;">
                        <p>This is a computer-generated document. No signature required.</p>
                        <p>${dairyName} | Generated on ${new Date().toLocaleString()}</p>
                    </div>
                </div>
            `;
            
            const printWindow = window.open('', '', 'width=900,height=600');
            printWindow.document.write(billHTML);
            printWindow.document.close();
            printWindow.print();
        }
        
        function handleNewBill(event) {
            event.preventDefault();
            alert('New bill created successfully!');
            document.getElementById('newBillModal').style.display = 'none';
            document.getElementById('farmerName').value = '';
            document.getElementById('billPeriod').value = '';
            document.getElementById('billLiters').value = '';
            document.getElementById('billAmount').value = '';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('newBillModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
