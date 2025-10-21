<?php
class Appointments extends Controller{
    private $apModel;
    public function __construct(){ $this->apModel = $this->model('Appointment'); }

    // PATIENT
    public function my(){
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') return redirect('Pages/index');
        $data = [ 'appointments' => $this->apModel->getByPatient($_SESSION['user_id']) ];
        $this->view('pages/v_patient_appointments', $data);
    }

    // DOCTOR
    public function doctor(){
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') return redirect('Pages/index');
        $status = $_GET['status'] ?? null;
        $data = [
            'appointments' => $this->apModel->getByDoctor($_SESSION['user_id'], $status),
            'status' => $status
        ];
        $this->view('pages/v_doctor_appointments', $data);
    }


    // PATIENT: book a slot
public function book(){
    if($_SERVER['REQUEST_METHOD'] !== 'POST') return redirect('Appointments/my');
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') return redirect('Pages/index');

    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $date = $_POST['date'] ?? '';
    $from = $_POST['from'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    $starts = strtotime("$date $from");
    if(!$doctor_id || !$starts){
        $_SESSION['flash'] = 'Invalid date/time.';
        return redirect('Appointments/my');
    }

    // fixed duration: 15 minutes
    $durationMin = 15;
    $ends = $starts + $durationMin * 60;

    // (optional) ensure start is in the future
    if($starts <= time()){
        $_SESSION['flash'] = 'Start time must be in the future.';
        return redirect('Appointments/my');
    }

    $ok = $this->apModel->request([
        'patient_id' => $_SESSION['user_id'],
        'doctor_id'  => $doctor_id,
        'starts_at'  => date('Y-m-d H:i:s', $starts),
        'ends_at'    => date('Y-m-d H:i:s', $ends),
        'reason'     => $reason
    ]);

    $_SESSION['flash'] = $ok ? 'Appointment requested (15 min).' : 'Could not create request.';
    return redirect('Appointments/my');
}


    // DOCTOR: set status (approve/reject/cancel/complete)
public function setStatus($id, $new){
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') return redirect('Pages/index');

    $id = (int)$id;
    $new = strtolower($new);

    if($new === 'approved'){
        $ok = $this->apModel->approve($id);
        $_SESSION['flash'] = $ok ? 'Approved.' : 'Cannot approve: overlaps an approved appointment.';
        return redirect('Appointments/doctor');
    }

    $ok = $this->apModel->setStatus($id, $new);
    $_SESSION['flash'] = $ok ? ucfirst($new).'.' : 'Update failed.';
    return redirect('Appointments/doctor');
}

public function findDoctors(){
    // Only patients should call this (optional but good)
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
        http_response_code(403);
        echo json_encode([]);
        return;
    }

    $q = trim($_GET['q'] ?? '');
    header('Content-Type: application/json');

    if ($q === '' || mb_strlen($q) < 1) {
        echo json_encode([]);
        return;
    }

    // ask the model for matches
    $matches = $this->apModel->searchDoctorsByName($q);
    // return only what the UI needs
    $out = array_map(function($row){
        return ['id' => (int)$row->id, 'name' => $row->name];
    }, $matches ?? []);
    echo json_encode($out);
}


}
?>
