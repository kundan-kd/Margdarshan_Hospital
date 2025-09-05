<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Bill Print</title>
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
            background: #810100;
            color: #fff;
        }
        .status-discharge {
            background: #04860b;
            color: #fff;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }
        .bill-detail{
            font-weight: 600 ;
        }

        /* Enhanced Print Styles */
        @media print {
            /* Force background colors and images to print */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .invoice-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: none !important;
                width: 100% !important;
                margin: 0 !important;
            }

            /* Preserve header colors */
            .invoice-header {
                background: #2c5aa0 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .invoice-header::before {
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="rgba(255,255,255,0.1)"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>') no-repeat center !important;
                background-size: 60px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Preserve table header colors */
            .services-table th {
                background: #2c5aa0 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Preserve section backgrounds */
            .patient-info, .billing-info {
                background: #f8f9fa !important;
                border-left: 4px solid #2c5aa0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .total-section {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .footer {
                background: #f8f9fa !important;
                border-top: 1px solid #e9ecef !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Preserve status badge colors */
            .status-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .status-pending {
                background: #810100 !important;
                color: white !important;
            }

            .status-discharge {
                background: #04860b !important;
                color: white !important;
            }

            .status-paid {
                background: #d4edda !important;
                color: #155724 !important;
            }

            .status-overdue {
                background: #f8d7da !important;
                color: #721c24 !important;
            }

            /* Preserve urgent notice styling */
            .urgent-notice {
                background: #fff3cd !important;
                border: 1px solid #ffeaa7 !important;
                border-left: 4px solid #f39c12 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Preserve colors for amounts and section titles */
            .section-title {
                color: #2c5aa0 !important;
            }

            .amount {
                color: #2c5aa0 !important;
            }

            .total-row.final {
                color: #2c5aa0 !important;
                border-top: 2px solid #2c5aa0 !important;
            }

            /* Table borders for print */
            .services-table {
                border: 1px solid #dee2e6 !important;
            }

            .services-table td {
                border-bottom: 1px solid #e9ecef !important;
            }

            /* Remove hover effects for print */
            .services-table tr:hover {
                background-color: transparent !important;
            }

            /* Page break control */
            .invoice-container {
                page-break-inside: avoid;
            }

            .services-table {
                page-break-inside: auto;
            }

            .services-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
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
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="hospital-info">
                <div>
                    <div class="hospital-logo">Margdarshan Hospital</div>
                    <div class="hospital-details">
                        G-86, Behind Manju Sinha Smriti Park,<br>Kankarbagh, Patna – 800020<br>
                        Phone: +91 8210595186<br>
                        Email: info@margdarshanhospital.com
                    </div>
                </div>
                <div class="invoice-title">Bill</div>
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
                        <span>John Doe</span>
                    </div>                
                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                         <span>15/03/1985</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span>+91 9876543210</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span>123 Main Street, Patna, Bihar</span>
                    </div>
                    
                </div>

                <div class="billing-info">
                    <div class="section-title">Billing Information</div>
                     <div class="info-row">
                        <span class="info-label">Patient ID:</span>
                        <span>PAT-2024-001</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Admission Date:</span>
                        <span>01/09/2025</span>
                    </div>
                  
                        <div class="info-row">
                            <span class="info-label">Discharge Date:</span>
                            <span>04/09/2025</span>
                        </div>
                  
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="status-badge status-discharge">Discharged</span>
                    </div>
                </div>
            </div>

            <!-- Services Table -->
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Sr.No.</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>01-09-2025</td>
                        <td>Room Charges</td>
                        <td>Private room for 3 days</td>
                        <td class="text-center">₹15,000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>01-09-2025</td>
                        <td>Doctor Consultation</td>
                        <td>General consultation</td>
                        <td class="text-center">₹2,000</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>02-09-2025</td>
                        <td>Medical Tests</td>
                        <td>Blood tests and X-ray</td>
                        <td class="text-center">₹3,500</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>03-09-2025</td>
                        <td>Medicines</td>
                        <td>Prescribed medications</td>
                        <td class="text-center">₹1,800</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>04-09-2025</td>
                        <td>Nursing Care</td>
                        <td>24-hour nursing services</td>
                        <td class="text-center">₹4,200</td>
                    </tr>
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span class="bill-detail">Subtotal:</span>
                    <span class="amount">₹26,500</span>
                </div>
                <div class="total-row">
                    <span class="bill-detail">Paid Amount:</span>
                    <span class="amount">₹20,000</span>
                </div>
                <div class="total-row final">
                    <span class="bill-detail">Due Amount:</span>
                    <span class="amount">₹6,500</span>
                </div>
            </div>

            <div class="urgent-notice">
                <strong>Important:</strong> Payment is due within 30 days of the invoice date. Late payments may incur additional charges. If you have questions about this bill or need to set up a payment plan, please contact our billing department at +91 8210595186 or email info@margdarshanhospital.com.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Margdarshan Hospital</strong> | Licensed Healthcare Facility | Contact: +91 8210595186</p>
            <p>For billing inquiries: info@margdarshanhospital.com</p>
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