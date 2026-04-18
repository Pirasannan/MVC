<?php
class Appointments extends Controller
{
    private $apModel;
    private $userModel;
    public function __construct()
    {
        $this->apModel = $this->model('Appointment');
        $this->userModel = $this->model('M_Users');
    }

    private function getCurrentPatientStatus()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            return null;
        }

        return strtolower((string)$this->userModel->getUserStatusById((int)$_SESSION['user_id']));
    }

    private function getCurrentDoctorStatus()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return null;
        }

        return strtolower((string)$this->userModel->getUserStatusById((int)$_SESSION['user_id']));
    }

    // PATIENT
    public function my()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') return redirect('Pages/index');

        $status = $this->getCurrentPatientStatus();
        if ($status === 'inactive') {
            $_SESSION['flash'] = 'Your account is deactivated. Please contact admin.';
            return redirect('Users/logout');
        }

        $appointments = $this->apModel->getByPatient($_SESSION['user_id']);

        // Check for pending reschedule requests
        $pendingReschedules = 0;
        foreach ($appointments as $apt) {
            if (($apt->reschedule_status ?? 'none') === 'pending_patient') {
                $pendingReschedules++;
            }
        }

        $data = [
            'appointments' => $appointments,
            'pending_reschedules' => $pendingReschedules,
            'patient_status' => $status ?: 'active'
        ];
        $this->view('pages/v_patient_appointments', $data);
    }

    // DOCTOR
    public function doctor()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor')
            return redirect('Pages/index');

        $doctorStatus = $this->getCurrentDoctorStatus();

        $pending  = $this->apModel->getByDoctor($_SESSION['user_id'], 'pending');
        $approved = $this->apModel->getByDoctor($_SESSION['user_id'], 'approved');



        /* ===== CALENDAR BLOCK (add this) ===== */
        $monthStr = $_GET['month'] ?? date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $monthStr)) {
            $monthStr = date('Y-m');
        }

        $tzLocal = new DateTimeZone('Asia/Colombo');
        $tzUTC   = new DateTimeZone('UTC');

        $firstLocal = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $monthStr . '-01 00:00:00', $tzLocal);
        $daysInMonth = (int)$firstLocal->format('t');
        $lastLocal   = $firstLocal->modify('+' . ($daysInMonth - 1) . ' days')->setTime(23, 59, 59);

        $dowFirst = (int)$firstLocal->format('N'); // 1=Mon..7=Sun
        $gridStartLocal = $firstLocal->modify('-' . ($dowFirst - 1) . ' days')->setTime(0, 0, 0);
        $dowLast = (int)$lastLocal->format('N');
        $gridEndLocal = $lastLocal->modify('+' . (7 - $dowLast) . ' days')->setTime(23, 59, 59);

        $fetchStartUtc = $firstLocal->setTimezone($tzUTC)->format('Y-m-d H:i:s');
        $fetchEndUtc   = $lastLocal->setTimezone($tzUTC)->format('Y-m-d H:i:s');

        $rows = $this->apModel->getApprovedBetweenForDoctor((int)$_SESSION['user_id'], $fetchStartUtc, $fetchEndUtc);

        $byDate = [];
        foreach ($rows as $r) {
            $dtUtc   = new DateTimeImmutable($r->starts_at, $tzUTC);
            $dtLocal = $dtUtc->setTimezone($tzLocal);
            $key = $dtLocal->format('Y-m-d');
            if (!isset($byDate[$key])) $byDate[$key] = [];
            $byDate[$key][] = [
                'time' => $dtLocal->format('H:i'),
                'name' => isset($r->patient_name) ? (string)$r->patient_name : ''
            ];
        }

        $prevMonth = $firstLocal->modify('-1 month')->format('Y-m');
        $nextMonth = $firstLocal->modify('+1 month')->format('Y-m');

        $cal = [
            'monthStr'    => $monthStr,
            'monthName'   => $firstLocal->format('F Y'),
            'gridStartTs' => $gridStartLocal->getTimestamp(),
            'gridEndTs'   => $gridEndLocal->getTimestamp(),
            'byDate'      => $byDate,
            'prevMonth'   => $prevMonth,
            'nextMonth'   => $nextMonth,
        ];
        /* ===== END CALENDAR BLOCK ===== */

        $data = [
            'pending'  => $pending,
            'approved' => $approved,
            'flash'    => $_SESSION['flash'] ?? null,
            'cal'      => $cal, // pass to view
            'doctor_status' => $doctorStatus ?: 'active',
        ];
        unset($_SESSION['flash']);


        $this->view('pages/v_doctor_appointments', $data);
    }




    // PATIENT: book a slot
    public function book()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return redirect('Appointments/my');
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') return redirect('Pages/index');

        $status = $this->getCurrentPatientStatus();
        if ($status === 'inactive') {
            $_SESSION['flash'] = 'Your account is deactivated. Please contact admin.';
            return redirect('Users/logout');
        }
        if ($status === 'suspended') {
            $_SESSION['flash'] = 'Your account is suspended. You cannot book appointments.';
            return redirect('Appointments/my');
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $doctor_id = (int)($_POST['doctor_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $from = trim($_POST['from'] ?? '');
        $reason = trim($_POST['reason'] ?? '');

        // Parse the user's chosen local time in Asia/Colombo
        $tzLocal = new DateTimeZone('Asia/Colombo');
        $dtStartLocal = DateTimeImmutable::createFromFormat('Y-m-d H:i', "$date $from", $tzLocal);

        if (!$doctor_id || !$dtStartLocal) {
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
        if ($dtStartUTC <= $nowUTC->modify('+60 seconds')) {
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
    public function setStatus($id, $new)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') return redirect('Pages/index');

        $doctorStatus = $this->getCurrentDoctorStatus();
        if ($doctorStatus === 'inactive') {
            $_SESSION['flash'] = 'Your account is deactivated. Please contact admin.';
            return redirect('Users/logout');
        }
        if ($doctorStatus === 'suspended') {
            $_SESSION['flash'] = 'Your account is suspended. You cannot manage appointments.';
            return redirect('Appointments/doctor');
        }

        $id = (int)$id;
        $new = strtolower($new);

        if ($new === 'approved') {
            $ok = $this->apModel->approve($id);
            $_SESSION['flash'] = $ok ? 'Approved.' : 'Cannot approve: overlaps an approved appointment.';
            return redirect('Appointments/doctor');
        }

        $ok = $this->apModel->setStatus($id, $new);
        $_SESSION['flash'] = $ok ? ucfirst($new) . '.' : 'Update failed.';
        return redirect('Appointments/doctor');
    }

    public function findDoctors()
    {
        // Only patients should call this (optional but good)
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            http_response_code(403);
            echo json_encode([]);
            return;
        }

        $status = $this->getCurrentPatientStatus();
        if ($status !== 'active') {
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
        $out = array_map(function ($row) {
            return ['id' => (int)$row->id, 'name' => $row->name];
        }, $matches ?? []);
        echo json_encode($out);
    }

    //doctor reschedule
    public function reschedule($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
            return redirect('Pages/index');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('Appointments/doctor');
        }

        $id = (int)$id;
        $newDT = $_POST['new_datetime'] ?? null;
        $msg = trim($_POST['message'] ?? '');

        if (!$newDT) {
            $_SESSION['flash'] = 'Please select a new date and time.';
            return redirect('Appointments/doctor');
        }

        // Convert datetime-local format to MySQL datetime format
        $newDT = str_replace('T', ' ', $newDT) . ':00';

        $ok = $this->apModel->proposeRescheduleByDoctor($id, $_SESSION['user_id'], $newDT, $msg);

        if ($ok) {
            $_SESSION['flash'] = 'Reschedule proposed. Waiting for patient to respond.';
        } else {
            $_SESSION['flash'] = 'Could not propose reschedule. Check time conflicts or status.';
        }

        return redirect('Appointments/doctor');
    }

    //patients accept reschedule
    public function reschedule_accept($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            return redirect('Pages/index');
        }

        $status = $this->getCurrentPatientStatus();
        if ($status !== 'active') {
            $_SESSION['flash'] = 'Your account is not allowed to manage appointments right now.';
            return redirect('Appointments/my');
        }

        $id = (int)$id;
        $ok = $this->apModel->patientAcceptReschedule($id, $_SESSION['user_id']);

        if ($ok) {
            $_SESSION['flash'] = 'Reschedule accepted. Appointment approved.';
        } else {
            $_SESSION['flash'] = 'That reschedule request is no longer valid.';
        }

        return redirect('Appointments/my');
    }

    //patients decline reschedule
    public function reschedule_decline($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
            return redirect('Pages/index');
        }

        $status = $this->getCurrentPatientStatus();
        if ($status !== 'active') {
            $_SESSION['flash'] = 'Your account is not allowed to manage appointments right now.';
            return redirect('Appointments/my');
        }

        $id = (int)$id;
        $ok = $this->apModel->patientDeclineReschedule($id, $_SESSION['user_id']);

        if ($ok) {
            $_SESSION['flash'] = 'Reschedule declined. The doctor will review your appointment.';
        } else {
            $_SESSION['flash'] = 'That reschedule request is no longer valid.';
        }

        return redirect('Appointments/my');
    }
}
