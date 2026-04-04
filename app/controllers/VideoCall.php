<?php
require_once APPROOT . '/libraries/StreamToken.php';

class VideoCall extends Controller {

    private $apModel;

    public function __construct() {
        $this->apModel = $this->model('Appointment');
    }

    // ── Pre-call screen ──────────────────────────────────────────────────────
    // URL: /VideoCall/precall/{appointmentId}
    public function precall($appointmentId = null) {
        $role = $_SESSION['user_role'] ?? null;
        if (!in_array($role, ['doctor', 'patient'], true)) {
            return redirect('Pages/index');
        }

        $appointmentId = (int)$appointmentId;
        $apt = $this->apModel->findWithNames($appointmentId);

        if (!$apt || strtolower($apt->status) !== 'approved') {
            $_SESSION['flash'] = 'Appointment not found or not yet approved.';
            return redirect($role === 'doctor' ? 'Appointments/doctor' : 'Appointments/my');
        }

        // Ownership check
        $userId = (int)$_SESSION['user_id'];
        if ($role === 'doctor'  && (int)$apt->doctor_id  !== $userId) return redirect('Pages/index');
        if ($role === 'patient' && (int)$apt->patient_id !== $userId) return redirect('Pages/index');

        $view = $role === 'doctor'
            ? 'pages/Videoconsultation/v_doctor_precall'
            : 'pages/Videoconsultation/v_patient_precall';

        $this->view($view, ['appointment' => $apt]);
    }

    // ── Live call room ───────────────────────────────────────────────────────
    // URL: /VideoCall/room/{appointmentId}
    public function room($appointmentId = null) {
        $role = $_SESSION['user_role'] ?? null;
        if (!in_array($role, ['doctor', 'patient'], true)) {
            return redirect('Pages/index');
        }

        $appointmentId = (int)$appointmentId;
        $apt = $this->apModel->findWithNames($appointmentId);

        if (!$apt || strtolower($apt->status) !== 'approved') {
            $_SESSION['flash'] = 'Appointment not found or not yet approved.';
            return redirect($role === 'doctor' ? 'Appointments/doctor' : 'Appointments/my');
        }

        $userId = (int)$_SESSION['user_id'];
        if ($role === 'doctor'  && (int)$apt->doctor_id  !== $userId) return redirect('Pages/index');
        if ($role === 'patient' && (int)$apt->patient_id !== $userId) return redirect('Pages/index');

        $streamUserId = (string)$userId;
        $userName     = $_SESSION['user_name'] ?? ($role === 'doctor' ? $apt->doctor_name : $apt->patient_name);

        $data = [
            'appointment'    => $apt,
            'stream_api_key' => STREAM_API_KEY,
            'stream_token'   => StreamToken::generate($streamUserId),
            'call_id'        => 'appointment_' . $appointmentId,
            'stream_user_id' => $streamUserId,
            'stream_user_name' => htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'),
            'is_doctor'      => ($role === 'doctor'),
        ];

        $view = $role === 'doctor'
            ? 'pages/Videoconsultation/v_doctor_videocall'
            : 'pages/Videoconsultation/v_patient_videocall';

        $this->view($view, $data);
    }
}
