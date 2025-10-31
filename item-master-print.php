<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$US = new User($_SESSION['id']);
$COMPANY_PROFILE = new CompanyProfile($US->company_id);

// Fetch all items
$ITEM_MASTER = new ItemMaster(NULL);
$db = new Database();
$sql = "SELECT 
    im.*,
    b.name as brand_name,
    c.name as category_name,
    g.name as group_name,
    st.name as stock_type_name
FROM item_master im
LEFT JOIN brands b ON im.brand = b.id
LEFT JOIN category_master c ON im.category = c.id
LEFT JOIN group_master g ON im.group = g.id
LEFT JOIN stock_type st ON im.stock_type = st.id
ORDER BY im.id DESC";

$result = $db->readQuery($sql);
$items = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
}
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Item Master Report | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icons -->
    <link href="assets/css/icons.min.css" rel="stylesheet" />
    <!-- Unicons CDN -->
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">
    <!-- App CSS -->
    <link href="assets/css/app.min.css" rel="stylesheet" />

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                margin: 15mm;
                size: landscape;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        body {
            font-size: 12px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            font-size: 11px;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 8px 5px;
            white-space: nowrap;
        }

        table td {
            padding: 6px 5px;
            vertical-align: middle;
        }

        .badge {
            padding: 3px 8px;
            font-size: 10px;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container-fluid mt-4">
        <!-- Print Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <h4>Item Master Report</h4>
            <div>
                <button onclick="window.print()" class="btn btn-success">
                    <i class="uil uil-print me-1"></i> Print / Save as PDF
                </button>
                <button onclick="window.close()" class="btn btn-secondary ms-2">
                    <i class="uil uil-times me-1"></i> Close
                </button>
            </div>
        </div>

        <!-- Report Header -->
        <div class="report-header">
            <?php if (!empty($COMPANY_PROFILE->image_name)): ?>
                <img src="./uploads/company-logos/<?php echo $COMPANY_PROFILE->image_name ?>" 
                     alt="logo" style="max-height: 60px; margin-bottom: 10px;">
            <?php endif; ?>
            <h3><?php echo $COMPANY_PROFILE->name ?></h3>
            <p class="mb-1"><?php echo $COMPANY_PROFILE->address ?></p>
            <p class="mb-1">
                <i class="uil uil-phone"></i> <?php echo $COMPANY_PROFILE->mobile_number_1 ?> 
                <?php if (!empty($COMPANY_PROFILE->email)): ?>
                    | <i class="uil uil-envelope"></i> <?php echo $COMPANY_PROFILE->email ?>
                <?php endif; ?>
            </p>
            <h4 class="mt-3"><strong>Item Master Report</strong></h4>
            <p>Generated on: <?php echo date('d M, Y h:i A'); ?></p>
        </div>

        <!-- Items Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Group</th>
                        <th>Size</th>
                        <th>Pattern</th>
                        <th class="text-end">List Price</th>
                        <th class="text-center">Discount %</th>
                        <th class="text-end">Selling Price</th>
                        <th>Stock Type</th>
                        <th class="text-center">Re-order Level</th>
                        <th class="text-center">Re-order Qty</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($items) > 0): ?>
                        <?php foreach ($items as $index => $item): ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['code']); ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['brand_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['category_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['group_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['size'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['pattern'] ?? '-'); ?></td>
                                <td class="text-end"><?php echo number_format($item['list_price'], 2); ?></td>
                                <td class="text-center"><?php echo number_format($item['discount'], 2); ?>%</td>
                                <td class="text-end"><?php echo number_format($item['invoice_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($item['stock_type_name'] ?? '-'); ?></td>
                                <td class="text-center"><?php echo $item['re_order_level'] ?? '-'; ?></td>
                                <td class="text-center"><?php echo $item['re_order_qty'] ?? '-'; ?></td>
                                <td class="text-center">
                                    <?php if ($item['is_active'] == 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="text-center">No items found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="15" class="text-center">
                            <strong>Total Items: <?php echo count($items); ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-4">
            <div class="row">
                <div class="col-6">
                    <p><strong>Printed By:</strong> <?php echo $_SESSION['username'] ?? 'Admin'; ?></p>
                </div>
                <div class="col-6 text-end">
                    <p><strong>Date:</strong> <?php echo date('d M, Y h:i A'); ?></p>
                </div>
            </div>
        </div>

    </div>

    <!-- JS -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Trigger print dialog on Enter key
        document.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                window.print();
            }
        });
    </script>
</body>

</html>
