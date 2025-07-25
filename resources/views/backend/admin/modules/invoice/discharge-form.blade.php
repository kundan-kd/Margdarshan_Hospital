<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Discharge Summary</title>
	<style>
	@media print {
		body {
			margin: 0;
		}
		.no-print {
			display: none;
		}
        .container{
            box-shadow: none !important;
        }
	}
	
	body {
		font-family: Arial, sans-serif;
		max-width: 8.5in;
		margin: 0 auto;
		padding: 20px;
		line-height: 1.4;
		color: #333;
        font-size: 14px;
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
		font-size: 18px;
		font-weight: 600;
		border-bottom: 1px solid #333;
		padding-bottom: 20px;
		margin-bottom: 30px;
	}
	
	.info-grid {
        padding-bottom: 15px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
        border-bottom: 1px solid #333;
    }
	
	.info-row {
		display: flex;
		margin-bottom: 5px;
	}
	
	.label {
		font-weight: 600;
		min-width: 140px;
	}
	
	.value {
		flex: 1;
	}
	
	.section {
		margin-bottom: 15px;
	}
	
	.section-title {
		font-weight: bold;
		text-decoration: underline;
		margin-bottom: 8px;
	}
	
	.vital-signs {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
		margin-bottom: 15px;
	}
	
	ul {
		margin: 5px 0;
		padding-left: 20px;
	}
	
	li {
		margin-bottom: 3px;
	}
	
	.medication-table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 15px;
	}
	
	.medication-table td {
		padding: 5px;
		border-bottom: 1px solid #ddd;
	}
	
	.medication-table .med-num {
		width: 30px;
		font-weight: 600;
	}
	
	.medication-table .med-name {
		width: 200px;
	}
	
	.signature-section {
		margin-top: 50px;
		display: flex;
		justify-content: space-between;
	}
	
	.print-button {
		background-color: #4CAF50;
		color: white;
		padding: 10px 20px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		margin-bottom: 20px;
	}
	
	.print-button:hover {
		background-color: #45a049;
	}
	</style>
</head>

<body>
    @php
        // dd($dischargeSummary);
    @endphp
	<div class="container">
		<div class="header">{{$patients[0]->discharge_type}} SUMMARY</div>
		<div class="info-grid">
			<div class="left-column">
				<div class="info-row"> <span class="label">Patient ID:</span> <span class="value">{{$patients[0]->patient_id}}</span> </div>
				<div class="info-row"> <span class="label">Patient Name:</span> <span class="value">{{$patients[0]->name}}</span> </div>
				<div class="info-row"> <span class="label">Address:</span> <span class="value">{{$patients[0]->address}}</span> </div>
				<div class="info-row"> <span class="label">Contact No:</span> <span class="value">{{$patients[0]->mobile}}</span> </div>
				<div class="info-row"> <span class="label">Consultant:</span> <span class="value">Dr. {{$patients[0]->doctorData->name ?? 'NA'}}</span> </div>
				<div class="info-row"> <span class="label">Department:</span> <span class="value">{{$patients[0]->type}}</span> </div>
			</div>
			<div class="right-column">
				<div class="info-row"> <span class="label">Bed No:</span> <span class="value">{{$patients[0]->bedData->bed_no ?? 'NA'}}</span> </div>
				<div class="info-row"> <span class="label">Age/Gender:</span> <span class="value">{{$patients[0]->gender}}</span> </div>
				<div class="info-row"> <span class="label">Admission:</span> <span class="value">{{$patients[0]->created_at->format('d-m-Y')}}</span> </div>
				<div class="info-row"> <span class="label">Discharge:</span> <span class="value">{{ date('d-m-Y', strtotime($patients[0]->discharge_date)) }}
</span> </div>
				<div class="info-row"> <span class="label">Patient Type:</span> <span class="value">General</span> </div>
			</div>
		</div>
		<div class="section">
			<div>{!!$dischargeSummary[0]->final_diagnosis!!}</div>
		</div>
		<div class="signature-section">
			<div>
				<div style="font-weight: bold;">Medical Officer</div>
			</div>
			<div>
				<div style="font-weight: bold;">Advice By</div>
			</div>
		</div>
		<div style="text-align:center; margin-top: 20px;"> 
            <button class="print-button no-print" onclick="window.print()">Print</button>
        </div>
		
	</div>
</body>

</html>