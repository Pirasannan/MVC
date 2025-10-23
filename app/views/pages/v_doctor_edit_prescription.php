<?php require APPROOT.'/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/form/e_pres.css?v=<?php echo filemtime(APPROOT.'/../public/css/components/form/e_pres.css'); ?>">

<div class="dashboard-container doctor">
	<div class="e_prescription_container ">
		<div class="header">
			<h1>Edit E-Prescription</h1>
		</div>

		<form id="prescriptionForm" method="POST" action="<?php echo URLROOT; ?>/Doctor/editPrescription/<?php echo $data['id']; ?>">
			<!-- SECTION 1: Medication Details -->
			<div class="section">
				<div class="section-header">1. Medication Details</div>

				<div class="form-group">
				<label for="patient_id">Select Patient:</label>
					<select name="patient_id" id="patient_id" required>
    					<option value="">-- Select Patient --</option>
    					<?php foreach ($data['patients'] as $patient): ?>
        					<option value="<?php echo $patient->id; ?>" <?php echo ($data['patient_id'] == $patient->id) ? 'selected' : ''; ?>>
            					<?php echo htmlspecialchars($patient->name); ?>
        					</option>
    					<?php endforeach; ?>
					</select>
					<span class="error" id="patientError"></span>
				</div>

				<div class="form-group">
					<label for="drugName" class="required">Drug Name / Product</label>
					<div class="autocomplete-container">
						<input type="text" id="drugName" name="drug_name" autocomplete="off" placeholder="Start typing drug name..." value="<?php echo htmlspecialchars($data['drug_name'] ?? ''); ?>" required>
						<div id="drugList" class="autocomplete-list hidden"></div>
					</div>
					<span class="error" id="drugNameError"></span>
				</div>

				<div class="form-group">
					<label for="formulation">Formulation / Strength</label>
					<input type="text" id="formulation" name="formulation" placeholder="Optional" value="<?php echo htmlspecialchars($data['formulation'] ?? ''); ?>">
				</div>

				<div class="form-group">
					<label for="route" class="required">Route of Administration</label>
					<select id="route" name="route" required>
						<option value="">Select route</option>
						<option value="Oral" <?php echo ($data['route'] ?? '') == 'Oral' ? 'selected' : ''; ?>>Oral</option>
						<option value="Topical" <?php echo ($data['route'] ?? '') == 'Topical' ? 'selected' : ''; ?>>Topical</option>
						<option value="Injection" <?php echo ($data['route'] ?? '') == 'Injection' ? 'selected' : ''; ?>>Injection</option>
						<option value="Inhalation" <?php echo ($data['route'] ?? '') == 'Inhalation' ? 'selected' : ''; ?>>Inhalation</option>
						<option value="Sublingual" <?php echo ($data['route'] ?? '') == 'Sublingual' ? 'selected' : ''; ?>>Sublingual</option>
						<option value="Rectal" <?php echo ($data['route'] ?? '') == 'Rectal' ? 'selected' : ''; ?>>Rectal</option>
						<option value="Vaginal" <?php echo ($data['route'] ?? '') == 'Vaginal' ? 'selected' : ''; ?>>Vaginal</option>
						<option value="Otic" <?php echo ($data['route'] ?? '') == 'Otic' ? 'selected' : ''; ?>>Otic</option>
						<option value="Ophthalmic" <?php echo ($data['route'] ?? '') == 'Ophthalmic' ? 'selected' : ''; ?>>Ophthalmic</option>
						<option value="Nasal" <?php echo ($data['route'] ?? '') == 'Nasal' ? 'selected' : ''; ?>>Nasal</option>
					</select>
					<span class="error" id="routeError"></span>
				</div>

				<!-- <div class="form-group">
					<label>
						<input type="checkbox" name="brand_substitution" value="1" <?php echo ($data['brand_substitution'] ?? 0) ? 'checked' : ''; ?>>
						Allow brand substitution
					</label>
				</div>

				<div class="form-group">
					<label>
						<input type="checkbox" id="prn" name="prn" value="1" <?php echo ($data['prn'] ?? 0) ? 'checked' : ''; ?>>
						PRN (As needed)
					</label>
				</div> -->

				<div id="prnFields" class="hidden">
    				<div class="form-row">
						<div class="form-group">
							<label for="maxPer24h" class="required">Max per 24h</label>
							<input type="number" id="maxPer24h" name="max_per_24h" min="1" value="<?php echo htmlspecialchars($data['max_per_24h'] ?? ''); ?>">
							<span class="error" id="maxPer24hError"></span>
						</div>
						<div class="form-group">
							<label for="prnIndication" class="required">Indication for PRN use</label>
							<input type="text" id="prnIndication" name="prn_indication" value="<?php echo htmlspecialchars($data['prn_indication'] ?? ''); ?>">
							<span class="error" id="prnIndicationError"></span>
						</div>
    				</div>
				</div>

			</div>

			<!-- SECTION 2: Dosage & Timing -->
			<div class="section">
				<div class="section-header">2. Dosage & Timing</div>

				<div class="form-row">
					<div class="form-group">
						<label for="doseAmount" class="required">Dose Amount</label>
						<input type="text" id="doseAmount" name="dose_amount" placeholder="e.g., 10" value="<?php echo htmlspecialchars($data['dose_amount'] ?? ''); ?>" required>
						<span class="error" id="doseAmountError"></span>
					</div>
					<div class="form-group">
						<label for="doseUnit" class="required">Dose Unit</label>
						<select id="doseUnit" name="dose_unit" required>
							<option value="">Select unit</option>
							<option value="mg" <?php echo ($data['dose_unit'] ?? '') == 'mg' ? 'selected' : ''; ?>>mg</option>
							<option value="g" <?php echo ($data['dose_unit'] ?? '') == 'g' ? 'selected' : ''; ?>>g</option>
							<option value="mcg" <?php echo ($data['dose_unit'] ?? '') == 'mcg' ? 'selected' : ''; ?>>mcg</option>
							<option value="mL" <?php echo ($data['dose_unit'] ?? '') == 'mL' ? 'selected' : ''; ?>>mL</option>
							<option value="L" <?php echo ($data['dose_unit'] ?? '') == 'L' ? 'selected' : ''; ?>>L</option>
							<option value="units" <?php echo ($data['dose_unit'] ?? '') == 'units' ? 'selected' : ''; ?>>units</option>
							<option value="puffs" <?php echo ($data['dose_unit'] ?? '') == 'puffs' ? 'selected' : ''; ?>>puffs</option>
							<option value="drops" <?php echo ($data['dose_unit'] ?? '') == 'drops' ? 'selected' : ''; ?>>drops</option>
						</select>
						<span class="error" id="doseUnitError"></span>
					</div>
				</div>

				<div class="form-group">
					<label for="frequency" class="required">Frequency</label>

			<select id="frequency" name="frequency" required>
				<option value="">Select frequency</option>
				<option value="OD" <?php echo ($data['frequency'] ?? '') == 'OD' ? 'selected' : ''; ?>>OD (Once daily)</option>
				<option value="BD" <?php echo ($data['frequency'] ?? '') == 'BD' ? 'selected' : ''; ?>>BD (Twice daily)</option>
				<option value="TDS" <?php echo ($data['frequency'] ?? '') == 'TDS' ? 'selected' : ''; ?>>TDS (Three times daily)</option>
				<option value="QID" <?php echo ($data['frequency'] ?? '') == 'QID' ? 'selected' : ''; ?>>QID (Four times daily)</option>
				<option value="Q6H" <?php echo ($data['frequency'] ?? '') == 'Q6H' ? 'selected' : ''; ?>>Q6H (Every 6 hours)</option>
				<option value="Q8H" <?php echo ($data['frequency'] ?? '') == 'Q8H' ? 'selected' : ''; ?>>Q8H (Every 8 hours)</option>
				<option value="custom" <?php echo ($data['frequency'] ?? '') == 'custom' ? 'selected' : ''; ?>>Every X hours</option>
				<option value="PRN" <?php echo ($data['frequency'] ?? '') == 'PRN' ? 'selected' : ''; ?>>PRN (As needed)</option>
			</select>
			
					<span class="error" id="frequencyError"></span>
				</div>

				<div id="customFrequencyField" class="hidden">
    				<div class="form-group">
        				<label for="customFrequency">Custom Frequency Interval (hours)</label>
        				<input type="number" id="customFrequency" name="custom_frequency" min="1" max="24" placeholder="e.g., 4" value="<?php echo htmlspecialchars($data['custom_frequency'] ?? ''); ?>">
					</div>
				</div>

				<div class="form-group">
					<label for="timeOfDay">Time of Day</label>
					<input type="text" id="timeOfDay" name="time_of_day" placeholder="e.g., 9:00 AM, 2:00 PM" value="<?php echo htmlspecialchars($data['time_of_day'] ?? ''); ?>">
				</div>

				<div class="form-group">
					<label for="mealRelation">Meal Relation</label>
					<select id="mealRelation" name="meal_relation">
						<option value="">Select relation</option>
						<option value="With food" <?php echo ($data['meal_relation'] ?? '') == 'With food' ? 'selected' : ''; ?>>With food</option>
						<option value="On empty stomach" <?php echo ($data['meal_relation'] ?? '') == 'On empty stomach' ? 'selected' : ''; ?>>On empty stomach</option>
						<option value="No preference" <?php echo ($data['meal_relation'] ?? '') == 'No preference' ? 'selected' : ''; ?>>No preference</option>
					</select>
				</div>

				<div class="form-row">
					<div class="form-group">
						<label for="durationValue">Duration</label>
						<input type="number" id="durationValue" name="duration_value" min="1" placeholder="e.g., 7" value="<?php echo htmlspecialchars($data['duration_value'] ?? ''); ?>">
					</div>
					<div class="form-group">
						<select id="durationType" name="duration_type">
							<option value="Days" <?php echo ($data['duration_type'] ?? '') == 'Days' ? 'selected' : ''; ?>>Days</option>
							<option value="Weeks" <?php echo ($data['duration_type'] ?? '') == 'Weeks' ? 'selected' : ''; ?>>Weeks</option>
							<option value="Months" <?php echo ($data['duration_type'] ?? '') == 'Months' ? 'selected' : ''; ?>>Months</option>
							<option value="Until stopped" <?php echo ($data['duration_type'] ?? '') == 'Until stopped' ? 'selected' : ''; ?>>Until stopped</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="specialInstructions">Special Instructions</label>
					<textarea id="specialInstructions" name="special_instructions" placeholder="e.g., Do not crush, Take with food"><?php echo htmlspecialchars($data['special_instructions'] ?? ''); ?></textarea>
				</div>
			</div>

			<!-- SECTION 3: Dispensing & Quantity -->
			<!-- <div class="section">
				<div class="section-header">3. Dispensing & Quantity</div>

				<div class="form-row">
					<div class="form-group">
						<label for="dispenseQuantity" class="required">Dispense Quantity</label>
						<input type="number" id="dispenseQuantity" name="dispense_quantity" min="1" value="<?php echo htmlspecialchars($data['dispense_quantity'] ?? ''); ?>" required>
						<span class="error" id="dispenseQuantityError"></span>
					</div>
					<div class="form-group">
						<label for="unitType" class="required">Unit Type</label>
						<select id="unitType" name="unit_type" required>
							<option value="">Select unit</option>
							<option value="Tablet" <?php echo ($data['unit_type'] ?? '') == 'Tablet' ? 'selected' : ''; ?>>Tablet</option>
							<option value="Capsule" <?php echo ($data['unit_type'] ?? '') == 'Capsule' ? 'selected' : ''; ?>>Capsule</option>
							<option value="mL" <?php echo ($data['unit_type'] ?? '') == 'mL' ? 'selected' : ''; ?>>mL</option>
							<option value="Bottle" <?php echo ($data['unit_type'] ?? '') == 'Bottle' ? 'selected' : ''; ?>>Bottle</option>
							<option value="Patch" <?php echo ($data['unit_type'] ?? '') == 'Patch' ? 'selected' : ''; ?>>Patch</option>
						</select>
						<span class="error" id="unitTypeError"></span>
					</div>
				</div>
			</div> -->

			<!-- SECTION 4: Diagnosis / Indication -->
			<div class="section">
				<div class="section-header">4. Diagnosis / Indication</div>

				<div class="form-group">
					<label for="diagnosis" class="required">Diagnosis / Indication for Use</label>
					<input type="text" id="diagnosis" name="diagnosis" placeholder="Enter diagnosis or indication" value="<?php echo htmlspecialchars($data['diagnosis'] ?? ''); ?>" required>
					<span class="error" id="diagnosisError"></span>
				</div>
			</div>

			<!-- SECTION 5: Validity -->
			<div class="section">
				<div class="section-header">5. Validity</div>

				<div class="form-group">
					<label for="validUntil">Valid Until</label>
					<input type="date" id="validUntil" name="valid_until" value="<?php echo htmlspecialchars($data['valid_until'] ?? ''); ?>">
				</div>

				<div class="form-group">
					<label for="pharmacyNote">Note to Pharmacy</label>
					<textarea id="pharmacyNote" name="pharmacy_note" placeholder="Optional communication from doctor to pharmacist"><?php echo htmlspecialchars($data['pharmacy_note'] ?? ''); ?></textarea>
				</div>
			</div>

			<!-- SECTION 6: Review & Confirm -->
			<div class="section">
				<div class="section-header">6. Review & Confirm</div>

				<div class="form-group">
					<label for="doctorNotes">Doctor Notes to Patient</label>
					<textarea id="doctorNotes" name="doctor_notes" placeholder="Instructions for the patient"><?php echo htmlspecialchars($data['doctor_notes'] ?? ''); ?></textarea>
				</div>
			</div>
			

			<div class="footer-buttons">
				<button type="submit" class="btn btn-primary">Update Prescription</button>
				<div class="single_acc_link">
					<a class="goback" href="<?php echo URLROOT; ?>/Pages/doctorPrescriptions">Go back</a>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="<?php echo URLROOT; ?>/public/js/e_pres.js?v=<?php echo filemtime(APPROOT.'/../public/js/e_pres.js'); ?>"></script>

<?php require APPROOT.'/views/inc/footer.php'; ?>