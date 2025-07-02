<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Bill Invoice</title>
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
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-top: 7px;
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
                        Patna, Bihar<br>
                        Pin: 800001<br>
                        Phone: 9876543210<br>
                        Email: mdhpatna@gmail.com
                    </div>
                </div>
                <div class="invoice-title">MEDICINE BILL</div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Meta Information -->
            @php
                 $patientData = \App\Models\Patient::where('id',$patient_id)->get();
            @endphp
            <div class="invoice-meta">
                <div class="patient-info">
                    <div class="section-title">Patient Information</div>
                    <div class="info-row">
                        <span class="info-label">Patient Name:</span>
                        <span>{{$patientData[0]->name}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Patient ID:</span>
                        <span>{{$patientData[0]->patient_id}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span>{{$patientData[0]->mobile}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span>{{$patientData[0]->address}}</span>
                    </div>
                    
                </div>

                <div class="billing-info">
                    <div class="section-title">Billing Information</div>
                    <div class="info-row">
                        <span class="info-label">Bill Number:</span>
                        <span>{{$billings[0]->bill_no}}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Invoice Date:</span>
                        <span>{{ date('d/m/Y', strtotime($billings[0]->created_at)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Services Table -->
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Sr.No.</th>
                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Tax(%)</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        @endphp
                        @foreach ( $billing_items as $items)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$items->medicineNameData->name}}</td>
                            <td>{{$items->batchData->batch_no}}</td>
                            <td>{{$items->expiry}}</td>
                            <td>{{$items->qty}}</td>
                            <td>{{$items->sales_price}}</td>
                            <td>{{$items->amount}}</td>
                            <td>{{$items->tax_per}}</td>
                            <td style="text-align:right;">{{$items->amount + $items->tax_amount}}</td>
                        </tr>
                        @php
                          $i++
                        @endphp
                        @endforeach
                </tbody>
            </table>

            <!-- Total Section -->
            <div class="total-section">
                {{-- <div class="total-row">
                    <span class="bill-detail">Subtotal:</span>
                    <span class="amount">₹ {{$billings[0]->total_amount}}</span>
                </div>
                <div class="total-row">
                    <span class="bill-detail">Tax Amount:</span>
                    <span class="amount">₹ {{$billings[0]->taxes}}</span>
                </div> --}}
                <div class="total-row">
                    <span class="bill-detail">Net Amount:</span>
                    <span class="amount">₹ {{round($billings[0]->net_amount)}}</span>
                </div>
                <div class="total-row" style="{{ $billings[0]->discount_amount > 0 ? '' : 'display:none;' }}">
                    <span class="bill-detail">Discount Amount:</span>
                    <span class="amount">₹ {{round($billings[0]->discount_amount)}}</span>
                </div>
                <div class="total-row">
                    <span class="bill-detail">Paid Amount:</span>
                    <span class="amount">₹ {{round($billings[0]->paid_amount)}}</span>
                </div>
                <div class="total-row">
                    <span class="bill-detail">Due:</span>
                    <span class="amount">₹ {{round($billings[0]->due_amount)}}</span>
                </div>
            </div>

            {{-- <div class="payment-info">
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
           
            <div class="urgent-notice">
                <strong>Important:</strong> Payment is due within 30 days of the invoice date. Late payments may incur additional charges. If you have questions about this bill or need to set up a payment plan, please contact our billing department at (555) 123-4567 Ext. 2 or email billing@medicarehospital.com.
            </div> --}}
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Margdarsan Hospital</strong> | Licensed Healthcare Facility | Contact: 9876543210</p>
            <p>For billing inquiries: mdhpatna@gmail.com</p>
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