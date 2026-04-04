<?php

class Video extends Controller {
    private $appointmentModel;

    public function __construct() {
        $this->appointmentModel = $this->model('Appointment');
    }

    public function token($appointmentId = 0) {
        if (!isset($_SESSION['user_id'], $_SESSION['user_role'])) {
            return $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }

        $role = strtolower((string)$_SESSION['user_role']);
        $userId = (int)$_SESSION['user_id'];
        $appointmentId = (int)$appointmentId;

        if ($appointmentId <= 0 || ($role !== 'doctor' && $role !== 'patient')) {
            return $this->jsonResponse(['error' => 'Invalid request'], 400);
        }

        if ($role === 'doctor') {
            $appointment = $this->appointmentModel->getByIdForDoctor($appointmentId, $userId);
            $streamUserId = 'user-' . (int)$userId;
            $displayName = 'Dr. ' . trim((string)($_SESSION['user_name'] ?? ('Doctor ' . $userId)));
        } else {
            $appointment = $this->appointmentModel->getByIdForPatient($appointmentId, $userId);
            $streamUserId = 'user-' . (int)$userId;
            $displayName = trim((string)($_SESSION['user_name'] ?? ('Patient ' . $userId)));
        }

        if (!$appointment || strtolower((string)$appointment->status) !== 'approved') {
            return $this->jsonResponse(['error' => 'Appointment not allowed for call'], 403);
        }

        $token = $this->generateStreamUserToken($streamUserId);

        return $this->jsonResponse([
            'token' => $token,
            'apiKey' => STREAM_API_KEY,
            'callType' => STREAM_CALL_TYPE,
            'callId' => 'appointment-' . $appointmentId,
            'user' => [
                'id' => $streamUserId,
                'name' => $displayName,
            ],
        ]);
    }

    private function generateStreamUserToken($streamUserId) {
        $iat = time();
        $ttlSeconds = max(60, (int)STREAM_TOKEN_TTL_MINUTES * 60);
        $exp = $iat + $ttlSeconds;

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => 'https://pronto.getstream.io',
            'sub' => 'user/' . $streamUserId,
            'user_id' => $streamUserId,
            'validity_in_seconds' => $ttlSeconds,
            'iat' => $iat,
            'exp' => $exp,
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, STREAM_API_SECRET, true);

        return $encodedHeader . '.' . $encodedPayload . '.' . $this->base64UrlEncode($signature);
    }

    private function base64UrlEncode($value) {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function jsonResponse($payload, $status = 200) {
        http_response_code((int)$status);
        header('Content-Type: application/json');
        echo json_encode($payload);
        return;
    }
}
