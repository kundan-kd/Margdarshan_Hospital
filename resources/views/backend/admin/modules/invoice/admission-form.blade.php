<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>MH Admission Form</title>
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
			<div class="form-title">ADMISSION FORM</div>
		</div>
		<div class="patient-info">
			<div class="info-group">
				<div class="info-row"> <span class="info-label">Patient ID:</span> <span class="info-value">{{$patients[0]->patient_id}}</span> </div>
				<div class="info-row"> <span class="info-label">Admit Date & Time:</span> <span class="info-value">{{$patients[0]->created_at->format('d-m-Y h:i A')}}</span> </div>
				<div class="info-row"> <span class="info-label">Patient Name:</span> <span class="info-value">{{$patients[0]->name}}</span> </div>
				<div class="info-row"> <span class="info-label">Guardian's Name:</span> <span class="info-value">{{$patients[0]->guardian_name}}</span> </div>
				<div class="info-row"> <span class="info-label">Marital Status:</span> <span class="info-value">{{$patients[0]->marital_status}}</span> </div>
				<div class="info-row"> <span class="info-label">Treat Consultant:</span> <span class="info-value">Dr. {{$patients[0]->doctorData->name ?? ''}}</span> </div>
				<div class="info-row"> <span class="info-label">Address:</span> <span class="info-value">{{$patients[0]->address}}</span> </div>
			
			</div>
			<div class="info-group">
				<div class="info-row"> <span class="info-label">IPD No:</span> <span class="info-value">{{$patients[0]->bedData->bed_no}}</span> </div>
				<div class="info-row"> <span class="info-label">Age / Sex:</span> <span class="info-value">{{$patients[0]->gender}}</span> </div>
				<div class="info-row"> <span class="info-label">Contact No:</span> <span class="info-value">{{$patients[0]->mobile}}</span> </div>
				<div class="info-row"> <span class="info-label">Department:</span> <span class="info-value">{{$patients[0]->type}}</span> </div>
				<div class="info-row"> <span class="info-label">Ref Person:</span> <span class="info-value">{{$patients[0]->reference_person ?? ''}}</span> </div>
                	<div class="info-row"> <span class="info-label">Patient Type:</span> <span class="info-value">{{$patients[0]->type}}</span> </div>
				<div class="info-row"> <span class="info-label">Brought By:</span> <span class="info-value">{{$patients[0]->guardian_name}}</span> </div>
			</div>
		</div>
		<div class="section-title">UNDERTAKING</div>
		<h4 style="margin-bottom: 10px;">MANAGEMENT OF THE HOSPITAL HAS RESERVED THE RIGHT OF ADMISSION OF THE PATIENT IN THIS HOSPITAL</h4>
		<ol>
			<li>I have been well informed by doctor(s) about patient’s present status of health and his illness(es).</li>
			<li>I have been fully explained about investigations, medications, their effects and possible ill-effects, or untoward reactions & possible complications.</li>
			<li>If need arises, I am, hereby, consenting to have additional consultation(s) from concerned specialists, on this patient.</li>
			<li>Also, I am taking responsibility of consequences and payment of shifting the patient to I.C.U. (Intensive Care Unit) if the need arises.</li>
			<li>I am consenting for necessary investigations (Tests) and treatment. If any ill-effects are likely to arise, I will get them explained from time to time, from treating doctor(s).</li>
			<li>Medicines, infusion-bottles & sets, etc., are not manufactured by the Hospital & I am also aware that medication from only standard companies are used in this Hospital.</li>
			<li>I shall enquire with the doctor(s) about patient’s progress from time to time.</li>
			<li>If the patient or any of his/her attendant damages any of Hospital’s equipment or encumbrances, I shall be responsible for it and I will pay for the damage / breakage separately.</li>
			<li>I am aware of the available facilities and also of non-availability of certain facilities in this Hospital.</li>
			<li>I hereby consent to examine and later to dispose off any of my / patient’s tissues / organs removed from my / patient’s body during surgery / procedure, as per the Hospital’s rules / regulations policies & routine.</li>
			<li>I consent to the taking and publication of any photographs / Video tapes / Audio tapes recorded on me / patient’s during any examination / procedure / surgery for up-liftment of medical knowledge. I presume that my / patient’s identity will not be disclosed.</li>
		</ol>
		<p style="margin-top:15px;"><strong>I / WE HAVE READ & UNDERSTOOD THE ABOVE RULES AND REGULATIONS. I / WE HEREBY AGREE TO ABIDE BY THEM AND REQUEST OF THE HOSPITAL AUTHORITIES TO ADMIT MYSELF / MY PATIENT.</strong></p>
		<div class="section-title">UNDERTAKING OF PAYMENT OF BILL(S)/EXPENSES</div>
		<p> I / We have been informed about the approximate expenses. I / We agree to pay the daily expenses & cost(s) incurred for the treatment / procedure / investigations. I / We voluntarily agree to pay the necessary deposit and weekly / final Bill(s) </p>
		<div class="signature">
			<p>Date: <sub>....................................</sub></p>
			<p>.......................................................
				<br>Signature of patient / patient party</p>
		</div>
		<div class="no-print" style="text-align:center; margin-top: 20px;">
			<button onclick="window.print()">Print this Page</button>
		</div>
	</div>
</body>

</html>