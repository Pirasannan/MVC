<?php require APPROOT.'/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/form/e_pres.css?v=<?php echo filemtime(APPROOT.'/../public/css/components/form/e_pres.css'); ?>">

<div class="dashboard-container doctor">
	<div class="e_prescription_container ">
		<div class="header">
			<h1>E-Prescription</h1>
		</div>

		<form id="prescriptionForm" method="POST" action="<?php echo URLROOT; ?>/Doctor/addPrescription">
			<!-- SECTION 1: Medication Details -->
			<div class="section">
				<div class="section-header">1. Medication Details</div>

				<div class="form-group">
					<label for="drugName" class="required">Drug Name / Product</label>
					<div class="autocomplete-container">
						<input type="text" id="drugName" name="drug_name" autocomplete="off" placeholder="Start typing drug name..." required>
						<div id="drugList" class="autocomplete-list hidden"></div>
					</div>
					<span class="error" id="drugNameError"></span>
				</div>

				<div class="form-group">
					<label for="formulation">Formulation / Strength</label>
					<input type="text" id="formulation" name="formulation" placeholder="Optional">
				</div>

				<div class="form-group">
					<label for="route" class="required">Route of Administration</label>
					<select id="route" name="route" required>
						<option value="">Select route</option>
						<option value="Oral">Oral</option>
						<option value="Intravenous">Intravenous</option>
						<option value="Intramuscular">Intramuscular</option>
						<option value="Subcutaneous">Subcutaneous</option>
						<option value="Topical">Topical</option>
						<option value="Ophthalmic">Ophthalmic</option>
						<option value="Inhalation">Inhalation</option>
						<option value="Rectal">Rectal</option>
						<option value="Sublingual">Sublingual</option>
					</select>
					<span class="error" id="routeError"></span>
				</div>

				<div class="form-group">
					<label class="checkbox-label">
						<input type="checkbox" id="brandSubstitution" name="brand_substitution" value="1">
						Brand substitution not permitted
					</label>
				</div>

				<div class="form-group">
					<label class="checkbox-label">
						<input type="checkbox" id="prn" name="prn" value="1">
						PRN (As needed)
					</label>
				</div>

				<div id="prnFields" class="hidden">
					<div class="form-row">
						<div class="form-group">
							<label for="maxPer24h" class="required">Max per 24h</label>
							<input type="number" id="maxPer24h" name="max_per_24h" min="1">
							<span class="error" id="maxPer24hError"></span>
						</div>
						<div class="form-group">
							<label for="prnIndication" class="required">Indication for PRN use</label>
							<input type="text" id="prnIndication" name="prn_indication">
							<span class="error" id="prnIndicationError"></span>
						</div>
					</div>
				</div>
			</div>

			<!-- SECTION 2: Dosage Instructions -->
			<div class="section">
				<div class="section-header">2. Dosage Instructions</div>

				<div class="form-row">
					<div class="form-group">
						<label for="doseAmount" class="required">Dose Amount</label>
						<input type="text" id="doseAmount" name="dose_amount" placeholder="e.g., 1" required>
						<span class="error" id="doseAmountError"></span>
					</div>
					<div class="form-group">
						<label for="doseUnit" class="required">Dose Unit</label>
						<select id="doseUnit" name="dose_unit" required>
							<option value="">Select unit</option>
							<option value="mg">mg</option>
							<option value="mL">mL</option>
							<option value="IU">IU</option>
							<option value="tablet">tablet</option>
							<option value="capsule">capsule</option>
							<option value="drops">drops</option>
						</select>
						<span class="error" id="doseUnitError"></span>
					</div>
				</div>

				<div class="form-group">
					<label for="frequency" class="required">Dose Frequency</label>
					<select id="frequency" name="frequency" required>
						<option value="">Select frequency</option>
						<option value="OD">OD (Once daily)</option>
						<option value="BD">BD (Twice daily)</option>
						<option value="TDS">TDS (Three times daily)</option>
						<option value="QID">QID (Four times daily)</option>
						<option value="Q6H">Q6H (Every 6 hours)</option>
						<option value="Q8H">Q8H (Every 8 hours)</option>
						<option value="custom">Every X hours</option>
						<option value="PRN">PRN (As needed)</option>
					</select>
					<span class="error" id="frequencyError"></span>
				</div>

				<div id="customFrequencyField" class="hidden">
					<div class="form-group">
						<label for="customFrequency">Custom Frequency Interval (hours)</label>
						<input type="number" id="customFrequency" name="custom_frequency" min="1" max="24" placeholder="e.g., 4">
					</div>
				</div>

				<div class="form-group">
					<label for="timeOfDay">Time(s) of Day</label>
					<input type="text" id="timeOfDay" name="time_of_day" placeholder="e.g., 9:00 AM, 9:00 PM">
				</div>

				<div class="form-group">
					<label for="mealRelation">Relation to Meals</label>
					<select id="mealRelation" name="meal_relation">
						<option value="Irrelevant">Irrelevant</option>
						<option value="Before meal">Before meal</option>
						<option value="With meal">With meal</option>
						<option value="After meal">After meal</option>
					</select>
				</div>

				<div class="form-row">
					<div class="form-group">
						<label for="durationValue">Duration</label>
						<input type="number" id="durationValue" name="duration_value" min="1" placeholder="e.g., 7">
						<span class="error" id="durationError"></span>
					</div>
					<div class="form-group">
						<label for="durationType">Duration Unit</label>
						<select id="durationType" name="duration_type">
							<option value="Days">Days</option>
							<option value="Weeks">Weeks</option>
							<option value="Months">Months</option>
							<option value="Until stopped">Until stopped</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="specialInstructions">Special Instructions</label>
					<textarea id="specialInstructions" name="special_instructions" placeholder="e.g., Do not crush, Take with food"></textarea>
				</div>
			</div>

			<!-- SECTION 3: Dispensing & Quantity -->
			<div class="section">
				<div class="section-header">3. Dispensing & Quantity</div>

				<div class="form-row">
					<div class="form-group">
						<label for="dispenseQuantity" class="required">Dispense Quantity</label>
						<input type="number" id="dispenseQuantity" name="dispense_quantity" min="1" required>
						<span class="error" id="dispenseQuantityError"></span>
					</div>
					<div class="form-group">
						<label for="unitType" class="required">Unit Type</label>
						<select id="unitType" name="unit_type" required>
							<option value="">Select unit</option>
							<option value="Tablet">Tablet</option>
							<option value="Capsule">Capsule</option>
							<option value="mL">mL</option>
							<option value="Bottle">Bottle</option>
							<option value="Patch">Patch</option>
						</select>
						<span class="error" id="unitTypeError"></span>
					</div>
				</div>
			</div>

			<!-- SECTION 4: Diagnosis / Indication -->
			<div class="section">
				<div class="section-header">4. Diagnosis / Indication</div>

				<div class="form-group">
					<label for="diagnosis" class="required">Diagnosis / Indication for Use</label>
					<input type="text" id="diagnosis" name="diagnosis" placeholder="Enter diagnosis or indication" required>
					<span class="error" id="diagnosisError"></span>
				</div>
			</div>

			<!-- SECTION 5: Validity -->
			<div class="section">
				<div class="section-header">5. Validity</div>

				<div class="form-group">
					<label for="validUntil">Valid Until</label>
					<input type="date" id="validUntil" name="valid_until">
				</div>

				<div class="form-group">
					<label for="pharmacyNote">Note to Pharmacy</label>
					<textarea id="pharmacyNote" name="pharmacy_note" placeholder="Optional communication from doctor to pharmacist"></textarea>
				</div>
			</div>

			<!-- SECTION 6: Review & Confirm -->
			<div class="section">
				<div class="section-header">6. Review & Confirm</div>

				<div class="form-group">
					<label for="doctorNotes">Doctor Notes to Patient</label>
					<textarea id="doctorNotes" name="doctor_notes" placeholder="Instructions for the patient"></textarea>
				</div>
			</div>
            <div class="section">
                <div class="section-header">Prescription Preview</div>
                    <div id="previewContent" class="preview-box">
                    <em>Fill out the form to see preview...</em>
                    </div>
            </div>


			<div class="footer-buttons">
				<button type="submit" class="btn btn-primary">Confirm Prescription</button>
				<div class="single_acc_link">
					<a class="goback" href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions">Go back</a>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="<?php echo URLROOT; ?>/public/js/e_pres.js?v=<?php echo filemtime(APPROOT.'/../public/js/e_pres.js'); ?>"></script>


<?php require APPROOT.'/views/inc/footer.php'; ?>