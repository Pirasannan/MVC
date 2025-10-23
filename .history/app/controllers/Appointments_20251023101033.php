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
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor')
        return redirect('Pages/index');

    $pending  = $this->apModel->getByDoctor($_SESSION['user_id'], 'pending');
    $approved = $this->apModel->getByDoctor($_SESSION['user_id'], 'approved');

    $data = [
        'pending'  => $pending,
        'approved' => $approved,
        'flash'    => $_SESSION['flash'] ?? null
    ];
    unset($_SESSION['flash']);

    $this->view('pages/v_doctor_appointments', $data);
}



    // PATIENT: book a slot
public function book(){
    if($_SERVER['REQUEST_METHOD'] !== 'POST') return redirect('Appointments/my');
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') return redirect('Pages/index');

    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $date = trim($_POST['date'] ?? '');
    $from = trim($_POST['from'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    // Parse the user's chosen local time in Asia/Colombo
    $tzLocal = new DateTimeZone('Asia/Colombo');
    $dtStartLocal = DateTimeImmutable::createFromFormat('Y-m-d H:i', "$date $from", $tzLocal);

    if(!$doctor_id || !$dtStartLocal){
        $_SESSION['flash'] = 'Invalid date/time.';
        return redirect('Appointments/my');
    }

    // Fixed duration: 15 minutes
    $dtEndLocal = $dtStartLocal->modify('+15 minutes');

    // Convert to UTC for storage & comparisons
    $tzUTC = new DateTimeZone('UTC');
    $dtStartUTC = $dtStartLocal->setTimezone($tzUTC);
    $dtEndUTC   = $dtEndLocal->setTimezone($tzUTC);

    // Compare against "now" in the SAME reference timezone (UTC)
    $nowUTC = new DateTimeImmutable('now', $tzUTC);

    // Small grace (60s) to avoid edge cases if seconds differ
    if($dtStartUTC <= $nowUTC->modify('+60 seconds')){
        $_SESSION['flash'] = 'Start time must be in the future.';
        return redirect('Appointments/my');
    }

    $ok = $this->apModel->request([
        'patient_id' => $_SESSION['user_id'],
        'doctor_id'  => $doctor_id,
        'starts_at'  => $dtStartUTC->format('Y-m-d H:i:s'),
        'ends_at'    => $dtEndUTC->format('Y-m-d H:i:s'),
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

//doctor reshedule
public function reschedule($id) {
    $this->requireDoctor();
    $newDT = $_POST['new_datetime'] ?? null; 
    $msg   = trim($_POST['message'] ?? '');
    $ok = $this->appointmentModel->proposeRescheduleByDoctor((int)$id, $_SESSION['doctor_id'], $newDT, $msg);
    if ($ok) {
        $this->notifyPatientOfProposal($id); 
        flash('appt_msg', 'Reschedule proposed. Waiting for patient to respond.');
    } else {
        flash('appt_err', 'Could not propose reschedule. Check time conflicts or status.');
    }
    redirect('Appointments/my'); 
}

//patients accept reschedule or decline
public function reschedule_accept($id) {
    $this->requirePatient();
    $ok = $this->appointmentModel->patientAcceptReschedule((int)$id, $_SESSION['patient_id']);
    if ($ok) {
        $this->notifyDoctorAccepted($id);
        flash('appt_msg', 'Reschedule accepted. Appointment approved.');
    } else {
        flash('appt_err', 'That reschedule request is no longer valid.');
    }
    redirect('Appointments/my');
}

public function reschedule_decline($id) {
    $this->requirePatient();
    $ok = $this->appointmentModel->patientDeclineReschedule((int)$id, $_SESSION['patient_id']);
    if ($ok) {
        $this->notifyDoctorDeclined($id);
        flash('appt_msg', 'Reschedule declined. The doctor will review your appointment.');
    } else {
        flash('appt_err', 'That reschedule request is no longer valid.');
    }
    redirect('Appointments/my');
}

private function requireDoctor(): void {
  if (empty($_SESSION['doctor_id'])) {
    flash('auth_err', 'Please log in as a doctor to continue.', 'alert alert-danger');
    redirect('Users/login'); // change to your login route if different
    exit;
  }
}


}
?>
