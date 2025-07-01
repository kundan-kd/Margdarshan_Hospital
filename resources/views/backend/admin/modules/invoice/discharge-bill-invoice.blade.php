<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Billing Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            padding: 30px;
            position: relative;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgba(255,255,255,0.1)"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>') no-repeat center;
            background-size: 60px;
        }

        .hospital-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .hospital-logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .hospital-details {
            font-size: 14px;
            opacity: 0.9;
        }

        .invoice-title {
            font-size: 36px;
            font-weight: bold;
            text-align: right;
            margin-top: -10px;
        }

        .invoice-body {
            padding: 30px;
        }

        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .patient-info, .billing-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2c5aa0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .services-table th {
            background: #2c5aa0;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .services-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .services-table tr:hover {
            background-color: #f8f9fa;
        }

        .services-table tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .amount {
            font-weight: 600;
            color: #2c5aa0;
        }

        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .total-row.final {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            padding-top: 10px;
            border-top: 2px solid #2c5aa0;
            margin-top: 15px;
        }

        .payment-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .payment-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1976d2;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .urgent-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #f39c12;
        }

        .urgent-notice strong {
            color: #856404;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .urgent-notice {
                background: white;
                border: 1px solid #333;
            }
        }

        @media (max-width: 768px) {
            .invoice-meta, .payment-info {
                grid-template-columns: 1fr;
            }
            
            .hospital-info {
                flex-direction: column;
                text-align: center;
            }
            
            .invoice-title {
                text-align: center;
                margin-top: 20px;
            }
            
            .services-table {
                font-size: 12px;
            }
            
            .services-table th,
            .services-table td {
                padding: 10px 5px;
            }
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="hospital-info">
                <div>
                    <div class="hospital-logo">🏥 Margdarshan Hospital</div>
                    <div class="hospital-details">
                        123 Health Street, Medical District<br>
                        New York, NY 10001<br>
                        Phone: (555) 123-4567<br>
                        Email: billing@medicarehospital.com
                    </div>
                </div>
                <div class="invoice-title">INVOICE</div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Meta Information -->
            <div class="invoice-meta">
                <div class="patient-info">
                    <div class="section-title">Patient Information</div>
                    <div class="info-row">
                        <span class="info-label">Patient Name:</span>
                        <span>John Michael Smith</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Patient ID:</span>
                        <span>PT-2024-001234</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                        <span>March 15, 1985</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span>456 Oak Avenue<br>Brooklyn, NY 11201</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span>(555) 987-6543</span>
                    </div>
                </div>

                <div class="billing-info">
                    <div class="section-title">Billing Information</div>
                    <div class="info-row">
                        <span class="info-label">Invoice Number:</span>
                        <span>INV-2024-007891</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Invoice Date:</span>
                        <span>July 01, 2025</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Due Date:</span>
                        <span>July 31, 2025</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Admission Date:</span>
                        <span>June 28, 2025</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Discharge Date:</span>
                        <span>June 30, 2025</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="status-badge status-pending">Pending</span>
                    </div>
                </div>
            </div>

            <!-- Services Table -->
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service Code</th>
                        <th>Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>RM-001</td>
                        <td>Private Room (2 nights)</td>
                        <td class="text-center">2</td>
                        <td class="text-right">$450.00</td>
                        <td class="text-right amount">$900.00</td>
                    </tr>
                    <tr>
                        <td>SG-025</td>
                        <td>Appendectomy Surgery</td>
                        <td class="text-center">1</td>
                        <td class="text-right">$8,500.00</td>
                        <td class="text-right amount">$8,500.00</td>
                    </tr>
                    <tr>
                        <td>AN-010</td>
                        <td>Anesthesia Services</td>
                        <td class="text-center">1</td>
                        <td class="text-right">$1,200.00</td>
                        <td class="text-right amount">$1,200.00</td>
                    </tr>
                    <tr>
                        <td>LAB-15</td>
                        <td>Blood Work Panel</td>
                        <td class="text-center">3</td>
                        <td class="text-right">$125.00</td>
                        <td class="text-right amount">$375.00</td>
                    </tr>
                    <tr>
                        <td>RAD-08</td>
                        <td>CT Scan - Abdomen</td>
                        <td class="text-center">1</td>
                        <td class="text-right">$750.00</td>
                        <td class="text-right amount">$750.00</td>
                    </tr>
                    <tr>
                        <td>MED-33</td>
                        <td>Medications</td>
                        <td class="text-center">1</td>
                        <td class="text-right">$285.00</td>
                        <td class="text-right amount">$285.00</td>
                    </tr>
                    <tr>
                        <td>ER-002</td>
                        <td>Emergency Room Fee</td>
                        <td class="text-center">1</td>
                        <td class="text-right">$650.00</td>
                        <td class="text-right amount">$650.00</td>
                    </tr>
                    <tr>
                        <td>DR-101</td>
                        <td>Physician Consultation</td>
                        <td class="text-center">3</td>
                        <td class="text-right">$200.00</td>
                        <td class="text-right amount">$600.00</td>
                    </tr>
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span class="amount">$13,260.00</span>
                </div>
                <div class="total-row">
                    <span>Insurance Coverage (80%):</span>
                    <span class="amount">-$10,608.00</span>
                </div>
                <div class="total-row">
                    <span>Patient Responsibility:</span>
                    <span class="amount">$2,652.00</span>
                </div>
                <div class="total-row">
                    <span>Tax (8.25%):</span>
                    <span class="amount">$218.79</span>
                </div>
                <div class="total-row final">
                    <span>Total Amount Due:</span>
                    <span>$2,870.79</span>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="payment-info">
                <div class="payment-section">
                    <div class="section-title">Insurance Information</div>
                    <div class="info-row">
                        <span class="info-label">Insurance Provider:</span>
                        <span>Blue Cross Blue Shield</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Policy Number:</span>
                        <span>BCBS-789456123</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Group Number:</span>
                        <span>GRP-45789</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Claim Number:</span>
                        <span>CLM-2024-556677</span>
                    </div>
                </div>

                <div class="payment-section">
                    <div class="section-title">Payment Instructions</div>
                    <div class="info-row">
                        <span class="info-label">Payment Due:</span>
                        <span>July 31, 2025</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Methods:</span>
                        <span>Online, Phone, Mail</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Online Portal:</span>
                        <span>www.medicarehospital.com/pay</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span>(555) 123-4567 Ext. 2</span>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="urgent-notice">
                <strong>Important:</strong> Payment is due within 30 days of the invoice date. Late payments may incur additional charges. If you have questions about this bill or need to set up a payment plan, please contact our billing department at (555) 123-4567 Ext. 2 or email billing@medicarehospital.com.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>MediCare General Hospital</strong> | Licensed Healthcare Facility | Tax ID: 12-3456789</p>
            <p>For billing inquiries: billing@medicarehospital.com | For medical records: records@medicarehospital.com</p>
            <p>This is a computer-generated invoice. Please retain this document for your records.</p>
        </div>
    </div>

    <script>
        // Print functionality
        function printInvoice() {
            window.print();
        }

        // Add print button functionality if needed
        document.addEventListener('DOMContentLoaded', function() {
            // You can add a print button here if needed
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    printInvoice();
                }
            });
        });

        // Status update functionality (for demo purposes)
        function updatePaymentStatus(status) {
            const statusElement = document.querySelector('.status-badge');
            statusElement.className = 'status-badge status-' + status;
            statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }
    </script>
</body>
</html>