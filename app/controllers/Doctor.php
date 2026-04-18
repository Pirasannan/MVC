<?php
class Doctor extends Controller {
    private $prescriptionModel;
    private $usersModel;

    public function __construct() {
        $this->prescriptionModel = $this->model('Prescription');
        $this->usersModel = $this->model('M_Users');
    }

	private function getCurrentDoctorStatus() {
		if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || strtolower((string)$_SESSION['user_role']) !== 'doctor') {
			return null;
		}

		return strtolower((string)$this->usersModel->getUserStatusById((int)$_SESSION['user_id']));
	}

    public function addPrescription() {
		$doctorStatus = $this->getCurrentDoctorStatus();
		if ($doctorStatus === 'inactive') {
			$_SESSION['flash'] = 'Your account is deactivated. You cannot create prescriptions.';
			return redirect('Users/logout');
		}

		if ($doctorStatus === 'suspended') {
			$_SESSION['flash'] = 'Your account is suspended. You cannot create prescriptions.';
			return redirect('Pages/doctorPrescriptions');
		}
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $patients = $this->usersModel->getPatients();
            $data = [
                'title' => 'Add Prescription',
                'patients' => $patients
            ];
            $this->view('pages/v_doctor_create_prescription', $data);
            return;
        }

       
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            'doctor_id' => $_SESSION['user_id'] ?? null,
			'patient_id' => $_POST['patient_id'] ?? null,
            'drug_name' => trim($_POST['drug_name'] ?? ''),
            'formulation' => trim($_POST['formulation'] ?? ''),
            'route' => trim($_POST['route'] ?? ''),
            'brand_substitution' => 0, // Set to 0 since field is commented out
            'prn' => 0, // Set to 0 since field is commented out
            'max_per_24h' => null, // Set to null since field is commented out
            'prn_indication' => null, // Set to null since field is commented out
            'dose_amount' => trim($_POST['dose_amount'] ?? ''),
            'dose_unit' => trim($_POST['dose_unit'] ?? ''),
            'frequency' => trim($_POST['frequency'] ?? ''),
            'custom_frequency' => $_POST['custom_frequency'] ?? null,
            'time_of_day' => trim($_POST['time_of_day'] ?? ''),
            'meal_relation' => trim($_POST['meal_relation'] ?? ''),
            'duration_value' => $_POST['duration_value'] ?? null,
            'duration_type' => $_POST['duration_type'] ?? null,
            'special_instructions' => trim($_POST['special_instructions'] ?? ''),
            'dispense_quantity' => null, // Set to null since field is commented out
            'unit_type' => null, // Set to null since field is commented out
            'diagnosis' => trim($_POST['diagnosis'] ?? ''),
            'valid_until' => $_POST['valid_until'] ?? null,
            'pharmacy_note' => trim($_POST['pharmacy_note'] ?? ''),
            'doctor_notes' => trim($_POST['doctor_notes'] ?? ''),
        ];

        $nullableStringFields = [
            'formulation', 'prn_indication', 'time_of_day', 'meal_relation',
            'special_instructions', 'pharmacy_note', 'doctor_notes'
        ];
        foreach ($nullableStringFields as $field) {
            if ($data[$field] === '') {
                $data[$field] = null;
            }
        }

        $nullableIntFields = ['max_per_24h', 'custom_frequency', 'duration_value'];
        foreach ($nullableIntFields as $field) {
            if ($data[$field] === '' || $data[$field] === null) {
                $data[$field] = null;
            } else {
                $data[$field] = (int)$data[$field];
            }
        }

        // dispense_quantity processing removed since field is commented out

        if ($data['valid_until'] === '') {
            $data['valid_until'] = null;
        }

        $required = [
            'doctor_id','patient_id' ,'drug_name', 'route', 'dose_amount', 'dose_unit',
            'frequency', 'diagnosis'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo 'Missing required field: ' . htmlspecialchars($field);
                exit;
            }
        }

        // Duration check (only if not "Until stopped")
        if (!empty($data['duration_type']) && $data['duration_type'] !== 'Until stopped') {
            if (empty($data['duration_value']) || (int)$data['duration_value'] <= 0) {
                http_response_code(400);
                echo 'Invalid duration.';
                exit;
            }
        }

        // PRN fields validation - commented out since PRN fields are disabled
        // if (!empty($data['prn'])) {
        //     if (empty($data['max_per_24h']) || (int)$data['max_per_24h'] <= 0 || empty($data['prn_indication'])) {
        //         http_response_code(400);
        //         echo 'PRN requires max_per_24h and prn_indication.';
        //         exit;
        //     }
        // }


        if ($this->prescriptionModel->addPrescription($data)) {
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions?created=1');            exit;
        }

        http_response_code(500);
        echo 'Something went wrong while saving prescription.';
        exit;
    }


	public function dashboard() {
		$doctor_id = $_SESSION['user_id'];
		$prescriptions = $this->prescriptionModel->getPrescriptionsByDoctor($doctor_id);
	
		$data = [
			'title' => 'Doctor Dashboard',
			'prescriptions' => $prescriptions
		];
	
		$this->view('pages/v_doctor_dashboard', $data);
	}
	
	public function editPrescription($id) {
		$doctorStatus = $this->getCurrentDoctorStatus();
		if ($doctorStatus === 'inactive') {
			$_SESSION['flash'] = 'Your account is deactivated. You cannot edit prescriptions.';
			return redirect('Users/logout');
		}

		if ($doctorStatus === 'suspended') {
			$_SESSION['flash'] = 'Your account is suspended. You cannot edit prescriptions.';
			return redirect('Pages/doctorPrescriptions');
		}

		$prescription = $this->prescriptionModel->getPrescriptionById($id);
	
		// Restrict edit access
		if (!$prescription || $prescription->doctor_id != $_SESSION['user_id']) {
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions');
			exit;
		}
	
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$patients = $this->usersModel->getPatients();
			$data = (array)$prescription;
			$data['patients'] = $patients;
			$this->view('pages/v_doctor_edit_prescription', $data);
			return;
		}
	
		// Handle POST submission - process form data similar to addPrescription
		$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$data = [
			'id' => $id,
			'doctor_id' => $_SESSION['user_id'],
			'patient_id' => $_POST['patient_id'] ?? null,
			'drug_name' => trim($_POST['drug_name'] ?? ''),
			'formulation' => trim($_POST['formulation'] ?? ''),
			'route' => trim($_POST['route'] ?? ''),
			'brand_substitution' => 0, // Set to 0 since field is commented out
			'prn' => 0, // Set to 0 since field is commented out
			'max_per_24h' => null, // Set to null since field is commented out
			'prn_indication' => null, // Set to null since field is commented out
			'dose_amount' => trim($_POST['dose_amount'] ?? ''),
			'dose_unit' => trim($_POST['dose_unit'] ?? ''),
			'frequency' => trim($_POST['frequency'] ?? ''),
			'custom_frequency' => $_POST['custom_frequency'] ?? null,
			'time_of_day' => trim($_POST['time_of_day'] ?? ''),
			'meal_relation' => trim($_POST['meal_relation'] ?? ''),
			'duration_value' => $_POST['duration_value'] ?? null,
			'duration_type' => $_POST['duration_type'] ?? null,
			'special_instructions' => trim($_POST['special_instructions'] ?? ''),
			'dispense_quantity' => null, // Set to null since field is commented out
			'unit_type' => null, // Set to null since field is commented out
			'diagnosis' => trim($_POST['diagnosis'] ?? ''),
			'valid_until' => $_POST['valid_until'] ?? null,
			'pharmacy_note' => trim($_POST['pharmacy_note'] ?? ''),
			'doctor_notes' => trim($_POST['doctor_notes'] ?? ''),
		];

		// Normalize values
		$nullableStringFields = [
			'formulation', 'prn_indication', 'time_of_day', 'meal_relation',
			'special_instructions', 'pharmacy_note', 'doctor_notes'
		];
		foreach ($nullableStringFields as $field) {
			if ($data[$field] === '') {
				$data[$field] = null;
			}
		}

		$nullableIntFields = ['max_per_24h', 'custom_frequency', 'duration_value'];
		foreach ($nullableIntFields as $field) {
			if ($data[$field] === '' || $data[$field] === null) {
				$data[$field] = null;
			} else {
				$data[$field] = (int)$data[$field];
			}
		}

		// dispense_quantity processing removed since field is commented out

		if ($data['valid_until'] === '') {
			$data['valid_until'] = null;
		}

		// Validation
		$required = [
			'doctor_id','patient_id' ,'drug_name', 'route', 'dose_amount', 'dose_unit',
			'frequency', 'diagnosis'
		];

		foreach ($required as $field) {
			if (empty($data[$field])) {
				http_response_code(400);
				echo 'Missing required field: ' . htmlspecialchars($field);
				exit;
			}
		}

		// Duration check (only if not "Until stopped")
		if (!empty($data['duration_type']) && $data['duration_type'] !== 'Until stopped') {
			if (empty($data['duration_value']) || (int)$data['duration_value'] <= 0) {
				http_response_code(400);
				echo 'Invalid duration.';
				exit;
			}
		}

		// PRN fields validation - commented out since PRN fields are disabled
		// if (!empty($data['prn'])) {
		//     if (empty($data['max_per_24h']) || (int)$data['max_per_24h'] <= 0 || empty($data['prn_indication'])) {
		//         http_response_code(400);
		//         echo 'PRN requires max_per_24h and prn_indication.';
		//         exit;
		//     }
		// }

		// Update prescription
		try {
			if ($this->prescriptionModel->updatePrescription($data)) {
				header('Location: ' . URLROOT . '/Pages/doctorPrescriptions?updated=1');
				exit;
			} else {
				http_response_code(500);
				echo 'Database update failed. Please check your data and try again.';
				exit;
			}
		} catch (Exception $e) {
			error_log('Prescription update error: ' . $e->getMessage());
			http_response_code(500);
			echo 'An error occurred while updating the prescription: ' . htmlspecialchars($e->getMessage());
			exit;
		}
	}
	



	public function deletePrescription($id) {
		// Check if prescription exists and belongs to the doctor
		$prescription = $this->prescriptionModel->getPrescriptionById($id);
		
		if (!$prescription || $prescription->doctor_id != $_SESSION['user_id']) {
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions');
			exit;
		}

		if ($this->prescriptionModel->softDeletePrescription($id, $_SESSION['user_id'])) {
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions?deleted=1');
			exit;
		} else {
			header('Location: ' . URLROOT . '/Pages/doctorPrescriptions?error=1');
			exit;
		}
	}

}
?>