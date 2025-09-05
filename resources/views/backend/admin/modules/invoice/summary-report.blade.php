<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Summary Report</title>
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
	
	.signature {
		margin-top: 50px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	
	.signature p {
		margin: 0;
	}

	/* Table styles for new sections */
	.data-table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 20px;
		font-size: 14px;
	}

	.data-table th {
		background-color: #f8f9fa;
		border: 1px solid #dee2e6;
		padding: 12px 8px;
		text-align: left;
		font-weight: 600;
		color: #333;
	}

	.data-table td {
		border: 1px solid #dee2e6;
		padding: 10px 8px;
		color: #666;
	}

	.data-table tr:nth-child(even) {
		background-color: #f8f9fa;
	}

	.data-table tr:hover {
		background-color: #e9ecef;
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
			<div class="hospital-details">G-86, Behind Manju Sinha Smriti Park,
                <br>Kankarbagh, Patna – 800020
				<br>Phone: +91 8210595186,
				<br> Email: info@margdarshanhospital.com</div>
			<div class="form-title">OPD SUMMARY REPORT</div>
		</div>
		<div class="patient-info">
			<div class="info-group">
				<div class="info-row"> <span class="info-label">UHID:</span> <span class="info-value">{{$patients[0]->patient_id}}</span> </div>
				<div class="info-row"> <span class="info-label">Visit Date:</span> <span class="info-value">{{$patients[0]->created_at->format('d-m-Y')}}</span> </div>
				<div class="info-row"> <span class="info-label">Patient Name:</span> <span class="info-value">{{$patients[0]->name}}</span> </div>
				<div class="info-row"> <span class="info-label">Guardian's Name:</span> <span class="info-value">{{$patients[0]->guardian_name}}</span> </div>
				<div class="info-row"> <span class="info-label">Marital Status:</span> <span class="info-value">{{$patients[0]->marital_status}}</span> </div>
				
			</div>
			<div class="info-group">
				<div class="info-row"> <span class="info-label">Department:</span> <span class="info-value">{{$patients[0]->type}}</span> </div>
                <div class="info-row"> <span class="info-label">Visit Time:</span> <span class="info-value">{{$patients[0]->created_at->format('h:i A')}}</span> </div>
				<div class="info-row"> <span class="info-label">Age / Sex:</span> <span class="info-value">{{$patients[0]->gender}}</span> </div>
				<div class="info-row"> <span class="info-label">Contact No:</span> <span class="info-value">{{$patients[0]->mobile}}</span> </div>
				<div class="info-row"> <span class="info-label">Address:</span> <span class="info-value">{{$patients[0]->address}}</span> </div>
			</div>
		</div>
		<div class="section-title">Description</div>
		<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
			<!-- Visits Section -->
			<div>
				<h4 style="background-color: #e9ecef; padding: 8px; margin-bottom: 10px; font-size: 14px; font-weight: 600;">Doctor Visits</h4>
				<table class="data-table">
					<thead>
						<tr>
							<th>Date & Time</th>
							<th>Doctor</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($visitsData as $visits)
						<tr>
							<td>{{$visits->updated_at->format('d-m-Y h:i A')}}</td>
							<td>Dr. {{$visits->doctorData->name ?? 'NA'}}</td>
						</tr>
                          @endforeach
					</tbody>
				</table>
			</div>

			<!-- Vitals Section -->
			<div>
				<h4 style="background-color: #e9ecef; padding: 8px; margin-bottom: 10px; font-size: 14px; font-weight: 600;">Patient Vitals</h4>
				<table class="data-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Parameter</th>
							<th>Value</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($vitalsData as $vitals)
						<tr>
							<td>{{$vitals->date}}</td>
							<td>{{$vitals->name}}</td>
							<td>{{$vitals->value}}</td>
						</tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>

		<!-- Second new section: Medication Details -->
		<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
			<!-- Visits Section -->
			<div>
				<h4 style="background-color: #e9ecef; padding: 8px; margin-bottom: 10px; font-size: 14px; font-weight: 600;">Medications</h4>
				<table class="data-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Medicine</th>
							<th>Dose</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($medicationData as $medication)
						<tr>
							<td>{{$medication->created_at->format('d-m-Y')}}</td>
							<td>{{$medication->medicineNameData->name}}</td>
							<td>{{$medication->dose}}</td>
						</tr>
                          @endforeach
					</tbody>
				</table>
			</div>

			<!-- Vitals Section -->
			<div>
				<h4 style="background-color: #e9ecef; padding: 8px; margin-bottom: 10px; font-size: 14px; font-weight: 600;">Lab Investigations</h4>
				<table class="data-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Test Name</th>
							<th>Parameter</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($labInvestigationData as $lab)
						<tr>
							<td>{{$lab->created_at->format('d-m-Y')}}</td>
							<td>{{$lab->testNameData->name}}</td>
							<td>{{$lab->test_parameter}}</td>
						</tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="no-print" style="text-align:center; margin-top: 20px;">
			<button onclick="window.print()"><i class="ri-printer-line" style="font-size: 20px;"></i></button>
		</div>
        
	</div>
</body>

</html>