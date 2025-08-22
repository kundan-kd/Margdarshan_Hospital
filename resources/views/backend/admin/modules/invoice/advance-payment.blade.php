<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>MH Admission Form</title>
	<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.min.css" rel="stylesheet">
	<style>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}
	
	body {
		font-family: Arial, sans-serif;
		background-color: #f5f5f5;
		color: #333;
		line-height: 1.6;
	}
	
	.container {
		max-width: 900px;
		margin: 20px auto;
		background: white;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
	}
	
	.header {
		text-align: center;
		border-bottom: 2px solid #333;
		padding-bottom: 20px;
		margin-bottom: 30px;
	}
	
	.hospital-logo img {
		width: 250px;
	}
	
	.hospital-name {
		font-size: 24px;
		font-weight: 600;
		margin-bottom: 5px;
	}
	
	.hospital-details {
		font-size: 14px;
		color: #444;
		margin-bottom: 10px;
	}
	
	.form-title {
		font-size: 18px;
		font-weight: 600;
		margin-top: 20px;
		text-decoration: underline;
	}
	
	h2,
	h3 {
		text-align: center;
	}
	
	li {
		margin-bottom: 10px;
	}
	
	ol {
		padding-left: 20px;
	}
	
	.patient-info {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
		margin-bottom: 30px;
	}
	
	.info-group {
		/* background: #f9f9f9; */
		/* padding: 20px; */
		/* border-radius: 8px; */
		/* border-left: 4px solid #007bff; */
	}
	
	.info-row {
		display: flex;
		margin-bottom: 4px;
		padding-right: 53px;
		font-size: 14px;
	}
	
	.info-label {
		font-weight: 600;
		min-width: 145px;
		color: #333;
	}
	
	.info-value {
		color: #666;
		flex: 1;
	}
	
	.section-title {
		font-weight: 600;
		background-color: #ddd;
		padding: 5px 10px;
		margin-top: 20px;
		margin-bottom: 20px;
	}
	
	.underline {
		text-decoration: underline;
	}

	/* Advance Payment Section Styles */
	.advance-payment-section {
		margin-top: 30px;
		margin-bottom: 30px;
	}

	.payment-table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 15px;
		border: 1px solid #ddd;
	}

	.payment-table th,
	.payment-table td {
		border: 1px solid #ddd;
		padding: 8px 12px;
		text-align: left;
		font-size: 14px;
	}

	.payment-table th {
		background-color: #f8f9fa;
		font-weight: 600;
		color: #333;
	}

	.payment-table tbody tr:nth-child(even) {
		background-color: #f9f9f9;
	}

	.payment-table tbody tr:hover {
		background-color: #f5f5f5;
	}

	.payment-total {
		margin-top: 10px;
		text-align: right;
		font-weight: 600;
		font-size: 16px;
		color: #333;
	}
	
	.signature {
		margin-top: 50px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	
	.signature p {
		margin: 0;
	}
	
	@media print {
		.no-print {
			display: none;
		}
		body {
			margin: 0;
			background: none;
		}
		.container {
			box-shadow: none;
			padding: 10px 30px;
		}
	}
	</style>
</head>

<body>
	<div class="container">
		<div class="header">
			<div class="hospital-logo"> <img src="https://mdh.techiesquad.in/backend/assets/images/logo.png" alt="logo" /> </div>
			{{-- <div class="hospital-details">G-86, Behind Manju Sinha Smriti Park,
                <br>Kankarbagh, Patna – 800020
				<br>Phone: +91 8210595186,
				<br> Email: info@margdarshanhospital.com</div> --}}
			<div class="form-title">ADVANCE PAYMENT</div>
		</div>
		<div class="patient-info">
			<div class="info-group">
				<div class="info-row"> <span class="info-label">Patient ID:</span> <span class="info-value">{{$patients[0]->patient_id}}</span> </div>
				<div class="info-row"> <span class="info-label">Admit Date & Time:</span> <span class="info-value">{{$patients[0]->created_at->format('d-m-Y h:i A')}}</span> </div>
			
			</div>
			<div class="info-group">
				<div class="info-row"> <span class="info-label">Patient Name:</span> <span class="info-value">{{$patients[0]->name}}</span> </div>
				<div class="info-row"> <span class="info-label">Department:</span> <span class="info-value">{{$patients[0]->type}}</span> </div>
			</div>
		</div>

		<!-- Advance Payment Section -->
		<div class="advance-payment-section">
			<div class="section-title">ADVANCE PAYMENT DETAILS</div>
			<table class="payment-table">
				<thead>
					<tr>
						<th>Date</th>
						<th>Amount</th>
						<th>Payment Mode</th>
					</tr>
				</thead>
				<tbody>
                    @foreach ($advanve_amount as $advance)
                        <tr>
                            <td>{{$advance->created_at->format('d-m-Y')}}</td>
                            <td>{{$advance->amount}}</td>
                            <td>{{$advance->payment_mode}}</td>
                        </tr>
                    @endforeach
				</tbody>
			</table>
		</div>
		<div class="no-print" style="text-align:center; margin-top: 20px;">
			<button onclick="window.print()"><i class="ri-printer-line" style="font-size: 20px;"></i></button>
		</div>
	</div>
</body>

</html>