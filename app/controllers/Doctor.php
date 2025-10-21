<?php
class Doctor extends Controller{
	private $prescriptionModel;

	public function __construct(){
		$this->prescriptionModel = $this->model('Prescription');
	}

	public function addPrescription(){
		if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions');
			exit;
		}

		$data = [
			'doctor_id' => $_SESSION['user_id'] ?? null,
			'drug_name' => trim($_POST['drug_name'] ?? ''),
			'formulation' => trim($_POST['formulation'] ?? ''),
			'route' => trim($_POST['route'] ?? ''),
			'brand_substitution' => isset($_POST['brand_substitution']) ? 1 : 0,
			'prn' => isset($_POST['prn']) ? 1 : 0,
			'max_per_24h' => $_POST['max_per_24h'] ?? null,
			'prn_indication' => $_POST['prn_indication'] ?? null,
			'dose_amount' => trim($_POST['dose_amount'] ?? ''),
			'dose_unit' => trim($_POST['dose_unit'] ?? ''),
			'frequency' => trim($_POST['frequency'] ?? ''),
			'custom_frequency' => $_POST['custom_frequency'] ?? null,
			'time_of_day' => trim($_POST['time_of_day'] ?? ''),
			'meal_relation' => trim($_POST['meal_relation'] ?? ''),
			'duration_value' => $_POST['duration_value'] ?? null,
			'duration_type' => $_POST['duration_type'] ?? null,
			'special_instructions' => trim($_POST['special_instructions'] ?? ''),
			'dispense_quantity' => $_POST['dispense_quantity'] ?? null,
			'unit_type' => $_POST['unit_type'] ?? '',
			'diagnosis' => trim($_POST['diagnosis'] ?? ''),
			'valid_until' => $_POST['valid_until'] ?? null,
			'pharmacy_note' => trim($_POST['pharmacy_note'] ?? ''),
			'doctor_notes' => trim($_POST['doctor_notes'] ?? ''),
		];

		// normalize empty strings to NULL where columns are nullable and cast numeric fields
		$nullableStringFields = ['formulation','prn_indication','time_of_day','meal_relation','special_instructions','pharmacy_note','doctor_notes'];
		foreach ($nullableStringFields as $field) {
			if ($data[$field] === '') { $data[$field] = null; }
		}

		$nullableIntFields = ['max_per_24h','custom_frequency','duration_value'];
		foreach ($nullableIntFields as $field) {
			if ($data[$field] === '' || $data[$field] === null) { $data[$field] = null; }
			else { $data[$field] = (int)$data[$field]; }
		}

		// required integer field
		if ($data['dispense_quantity'] !== null && $data['dispense_quantity'] !== '') {
			$data['dispense_quantity'] = (int)$data['dispense_quantity'];
		}

		// date field: empty string to NULL
		if ($data['valid_until'] === '') { $data['valid_until'] = null; }

		//server-side validation
		$required = ['doctor_id','drug_name','route','dose_amount','dose_unit','frequency','dispense_quantity','unit_type','diagnosis'];
		foreach ($required as $field) {
			if (empty($data[$field])) {
				http_response_code(400);
				echo 'Missing required field: ' . htmlspecialchars($field);
				exit;
			}
		}
		// Duration requirement only if not "Until stopped"
		if (!empty($data['duration_type']) && $data['duration_type'] !== 'Until stopped') {
			if (empty($data['duration_value']) || (int)$data['duration_value'] <= 0) {
				http_response_code(400);
				echo 'Invalid duration.';
				exit;
			}
		}
		// PRN fields if PRN checked
		if (!empty($data['prn'])) {
			if (empty($data['max_per_24h']) || (int)$data['max_per_24h'] <= 0 || empty($data['prn_indication'])) {
				http_response_code(400);
				echo 'PRN requires max_per_24h and prn_indication.';
				exit;
			}
		}

		if ($this->prescriptionModel->addPrescription($data)){
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions');
			exit;
		}

		http_response_code(500);
		echo 'Something went wrong while saving prescription.';
		exit;
	}
}
?>