# GetStream.io Video Call Integration

This document explains every file that was created or modified to add live video consultation between doctors and patients using the [GetStream.io Video SDK](https://getstream.io/video/).

---

## Table of Contents

1. [How it works — the big picture](#1-how-it-works--the-big-picture)
2. [Credentials & configuration](#2-credentials--configuration)
3. [File reference — new files](#3-file-reference--new-files)
4. [File reference — modified files](#4-file-reference--modified-files)
5. [URL routes & call flow (step by step)](#5-url-routes--call-flow-step-by-step)
6. [How the Stream SDK works in the browser](#6-how-the-stream-sdk-works-in-the-browser)
7. [How the call room ID is determined](#7-how-the-call-room-id-is-determined)
8. [How authentication / JWT tokens work](#8-how-authentication--jwt-tokens-work)
9. [Security checks in place](#9-security-checks-in-place)
10. [Common edits you might need to make](#10-common-edits-you-might-need-to-make)
11. [Troubleshooting](#11-troubleshooting)

---

## 1. How it works — the big picture

```
Doctor clicks "Start"
        │
        ▼
VideoCall::precall($id)          ← PHP verifies ownership, loads appointment
        │
        ▼
v_doctor_precall.php             ← Pre-call screen: shows patient name, time, reason
        │  clicks "Start Video Call"
        ▼
VideoCall::room($id)             ← PHP generates Stream JWT, passes all credentials to view
        │
        ▼
v_doctor_videocall.php           ← Browser loads Stream SDK, creates call room, shows live video


Patient clicks "Join Consultation"
        │
        ▼
VideoCall::precall($id)          ← Same controller, detects 'patient' role → different view
        │
        ▼
v_patient_precall.php            ← Pre-call screen: shows doctor name, time, reason
        │  clicks "Join Call"
        ▼
VideoCall::room($id)             ← Same controller action, patient gets their own JWT
        │
        ▼
v_patient_videocall.php          ← Browser joins the same call room (create: false)
```

Both participants end up in the **same call room** because they share the same `call_id` which is derived from the appointment ID (e.g. `appointment_42`).

---

## 2. Credentials & configuration

**File:** `app/config/config.php`

```php
define('STREAM_API_KEY',           'rks96nwm9y8b');
define('STREAM_API_SECRET',        'ez7m4fbuv7fzdbjwdm2g2znweaf6w8m3af79atywshun2xbyne2fbekpqxa3bdde');
define('STREAM_CALL_TYPE',         'default');
define('STREAM_TOKEN_TTL_MINUTES', 60);
```

| Constant | What it is | Where it's used |
|---|---|---|
| `STREAM_API_KEY` | Public key identifying your Stream app | Passed to the browser JS SDK |
| `STREAM_API_SECRET` | **Private** secret — never expose to the browser | Used server-side only in `StreamToken::generate()` to sign JWTs |
| `STREAM_CALL_TYPE` | The call type configured in your Stream dashboard (`default`) | Used in `streamClient.call(STREAM_CALL_TYPE, callId)` |
| `STREAM_TOKEN_TTL_MINUTES` | How long a generated token is valid (in minutes) | Used in `StreamToken.php` when setting the JWT `exp` claim |

> **Important:** `STREAM_API_SECRET` must **never** appear in JavaScript or HTML output. It is only used in `StreamToken.php` on the PHP side.

---

## 3. File reference — new files

### `app/libraries/StreamToken.php`

**Purpose:** Generates a signed JWT (JSON Web Token) that the browser passes to the Stream Video SDK to authenticate the user. No external libraries or Composer packages are needed — it uses PHP's built-in `hash_hmac` function.

**How it works:**

1. Builds a standard JWT `header` (`alg: HS256`, `typ: JWT`).
2. Builds the JWT `payload` containing:
   - `user_id` — the user's database ID (as a string)
   - `sub` — `user/{id}` (Stream's convention)
   - `iss` — issuer tag
   - `iat` — issued-at timestamp (Unix seconds)
   - `exp` — expiry timestamp (`iat + STREAM_TOKEN_TTL_MINUTES * 60`)
3. Signs `header.payload` with `HMAC-SHA256` using `STREAM_API_SECRET`.
4. Returns the final `header.payload.signature` JWT string.

**Usage:**
```php
$token = StreamToken::generate((string)$userId);
```

**When to edit this file:**
- If Stream changes their JWT format or required claims (check their changelog).
- If you need a shorter/longer token lifetime — change `STREAM_TOKEN_TTL_MINUTES` in `config.php` instead (no need to touch this file).
- If you switch to a Composer-managed SDK in the future, you may delete this file and use the official `stream-video-php` package instead.

---

### `app/controllers/VideoCall.php`

**Purpose:** Handles both the pre-call screen and the live call room for both doctors and patients. A single controller handles both roles — it detects the current user's role from `$_SESSION['user_role']` and renders the correct view.

**Two public methods:**

#### `precall($appointmentId)`
- URL: `/VideoCall/precall/{appointmentId}`
- Loads the appointment from the database using `findWithNames($id)`.
- Checks: appointment must exist, status must be `approved`, and the logged-in user must be the doctor or patient on that appointment (ownership check).
- Renders `v_doctor_precall.php` or `v_patient_precall.php` depending on role.
- Only passes `['appointment' => $apt]` to the view — no tokens yet.

#### `room($appointmentId)`
- URL: `/VideoCall/room/{appointmentId}`
- Performs the same security checks as `precall`.
- Calls `StreamToken::generate($userId)` to create a fresh JWT.
- Passes everything the browser needs to the view:

```php
$data = [
    'appointment'      => $apt,              // full appointment object
    'stream_api_key'   => STREAM_API_KEY,    // public key for JS SDK
    'stream_token'     => StreamToken::generate($streamUserId), // signed JWT
    'call_id'          => 'appointment_' . $appointmentId,      // unique room ID
    'stream_user_id'   => (string)$userId,   // user's DB id as string
    'stream_user_name' => $userName,         // display name shown in the call
    'is_doctor'        => ($role === 'doctor'), // boolean, not currently used in views but available
];
```

- Renders `v_doctor_videocall.php` or `v_patient_videocall.php` depending on role.

**When to edit this file:**
- To add more data to pass to the call room views (e.g. `appointment_type`, custom metadata).
- If you add call recording — add a `STREAM_CALL_TYPE` for a recording-enabled call type and update the constant.
- If you want to restrict the call to a time window (e.g. only joinable 5 minutes before `starts_at`), add a time check here before allowing `room()` to proceed.

---

## 4. File reference — modified files

### `app/config/config.php`

Added the four Stream constants described in [Section 2](#2-credentials--configuration).

---

### `app/models/Appointment.php`

Added one new method:

```php
public function findWithNames(int $id)
```

Fetches a single appointment by its primary key, JOINing the `Users` table twice to get `patient_name` and `doctor_name`. Returns a single object (or `false` if not found). This is used by both `VideoCall::precall()` and `VideoCall::room()`.

```sql
SELECT a.*,
       u1.name AS patient_name,
       u2.name AS doctor_name
FROM appointments a
JOIN Users u1 ON u1.id = a.patient_id
JOIN Users u2 ON u2.id = a.doctor_id
WHERE a.id = :id
LIMIT 1
```

---

### `app/controllers/Pages.php`

The old `doctorPrecall()` and `patientPrecall()` methods used to render the precall views with fake hardcoded data. They have been replaced with simple redirects:

```php
public function doctorPrecall() {
    redirect('Appointments/doctor');
}
public function patientPrecall() {
    redirect('Appointments/my');
}
```

This prevents the old routes (`/Pages/doctorPrecall`, `/Pages/patientPrecall`) from crashing if someone navigates to them, since the views now require a real appointment passed from the `VideoCall` controller.

The same was done for `doctorVideoCall()` and `patientVideoCall()` — those old routes now redirect to the appointments list.

---

### `app/views/pages/v_doctor_appointments.php`

The "Start" button for each approved appointment was changed from:
```php
// OLD – hardcoded, no appointment context
href="<?= URLROOT ?>/Pages/doctorPrecall"
```
To:
```php
// NEW – passes the appointment ID
href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>"
```

---

### `app/views/pages/v_patient_appointments.php`

The "Join Consultation" link was changed from:
```php
// OLD
href="<?= URLROOT ?>/Pages/patientPrecall"
```
To:
```php
// NEW
href="<?= URLROOT ?>/VideoCall/precall/<?= $a->id ?>"
```

---

### `app/views/pages/Videoconsultation/v_doctor_precall.php`

The top of the file now reads the `appointment` object passed by the controller:
```php
$apt = $data['appointment'];
$appointmentId = (int)$apt->id;
```

All previously hardcoded values (patient name, time, reason) now come from `$apt`. The "Start Video Call" button is now an `<a>` tag pointing to `/VideoCall/room/{appointmentId}`.

---

### `app/views/pages/Videoconsultation/v_patient_precall.php`

Same as the doctor precall, but shows the doctor's name from `$apt->doctor_name`. The "Join Call" link points to `/VideoCall/room/{appointmentId}`.

---

### `app/views/pages/Videoconsultation/v_doctor_videocall.php`

The video call room view for the doctor. Key changes:

- Added `$apt = $data['appointment']` at the top so real names are displayed.
- Replaced the static placeholder `<div>` containers with real `<video>` elements:
  ```html
  <video id="remote-video" autoplay playsinline>  <!-- patient's camera -->
  <video id="local-video"  autoplay playsinline muted>  <!-- doctor's own camera -->
  ```
- Replaced all the old fake JavaScript with an ES module that uses the Stream Video SDK (loaded from `esm.sh` CDN).
- The doctor calls `streamCall.join({ create: true })` — this **creates** the call room on Stream's servers.

---

### `app/views/pages/Videoconsultation/v_patient_videocall.php`

Identical structure to the doctor view. The only difference is:
- Patient calls `streamCall.join({ create: false })` — this **joins** a room that the doctor already created.
- End call redirects to `/Appointments/my` instead of `/Appointments/doctor`.

---

## 5. URL routes & call flow (step by step)

All URLs follow the MVC pattern: `/{Controller}/{method}/{param}`.

| Step | URL | Who visits | What happens |
|---|---|---|---|
| 1 | `/Appointments/doctor` | Doctor | Sees list of approved appointments with a "Start" button |
| 2 | `/VideoCall/precall/42` | Doctor | Pre-call screen — sees patient name, date/time, reason. No video yet |
| 3 | `/VideoCall/room/42` | Doctor | JWT generated; Stream SDK initialises; call room created; camera/mic enabled |
| 4 | `/Appointments/my` | Patient | Sees the same appointment with a "Join Consultation" button |
| 5 | `/VideoCall/precall/42` | Patient | Pre-call screen — sees doctor name, date/time, reason |
| 6 | `/VideoCall/room/42` | Patient | JWT generated; Stream SDK initialises; patient joins the existing room |

Both doctor and patient land on the **same call room** because both use the URL `/VideoCall/room/42`, and both will have `call_id = 'appointment_42'` injected into the JavaScript.

---

## 6. How the Stream SDK works in the browser

The Stream JavaScript SDK is loaded directly from the CDN — no npm/build step needed:

```js
import { StreamVideoClient } from 'https://esm.sh/@stream-io/video-client@1';
```

The initialization sequence in both videocall views:

```js
// 1. Create a client, authenticating the logged-in user
const streamClient = new StreamVideoClient({
    apiKey: API_KEY,       // from PHP config
    user:   { id: USER_ID, name: USER_NAME },
    token:  TOKEN,         // JWT signed server-side by StreamToken.php
});

// 2. Get a reference to this call room
const streamCall = streamClient.call('default', 'appointment_42');

// 3. Doctor creates; patient joins
await streamCall.join({ create: true/false });

// 4. Start local camera and mic
await streamCall.camera.enable();
await streamCall.microphone.enable();

// 5. Subscribe to the local camera stream → bind to <video id="local-video">
streamCall.camera.state.mediaStream$.subscribe(stream => {
    localVideoEl.srcObject = stream;
});

// 6. Subscribe to remote participants → bind their video stream to <video id="remote-video">
streamCall.state.remoteParticipants$.subscribe(participants => {
    if (participants.length > 0) {
        remoteVideoEl.srcObject = participants[0].videoStream;
    }
});
```

The `$` suffix on `mediaStream$` and `remoteParticipants$` means these are **RxJS Observables** — they emit a new value whenever something changes (e.g. a participant joins or the camera stream updates). The `.subscribe(callback)` call runs your callback every time a new value is emitted.

---

## 7. How the call room ID is determined

The `call_id` is constructed in `VideoCall::room()`:

```php
'call_id' => 'appointment_' . $appointmentId,
```

This means:
- Each appointment gets its own dedicated call room.
- As long as both participants navigate to the same appointment's room URL, they will automatically connect to each other.
- The call room persists on Stream's servers. If someone leaves and rejoins, they land in the same room.
- There is **no need to store the call ID in the database** — it can always be reconstructed from the appointment ID.

---

## 8. How authentication / JWT tokens work

Stream uses JWT (JSON Web Tokens) to authenticate users. The flow:

```
PHP Server                              Stream's servers
──────────                              ───────────────
1. User logs in → session has user_id
2. User opens /VideoCall/room/42
3. StreamToken::generate($userId)
   ├─ Creates header + payload JSON
   ├─ Signs with STREAM_API_SECRET
   └─ Returns "header.payload.signature"
4. PHP injects token into the HTML page
                                        
Browser                                 Stream's servers
───────                                 ────────────────
5. JS SDK sends the token to Stream ──→ Stream verifies the signature
                                        using your API secret
                                        (they know it from your dashboard)
                                    ←── Stream accepts the connection
6. streamCall.join() proceeds normally
```

**Key points:**
- The `STREAM_API_SECRET` is **only on the PHP server** — Stream verifies it on their side.
- The token embeds `user_id`, which Stream uses to identify who is participating.
- Tokens expire after `STREAM_TOKEN_TTL_MINUTES` (60 minutes by default). If a call lasts longer than that, the SDK handles token refresh automatically if you provide a `tokenProvider` callback. Currently the token is hardcoded into the page, which is fine for calls under an hour.

---

## 9. Security checks in place

Both `VideoCall::precall()` and `VideoCall::room()` perform three checks before doing anything:

1. **Authentication** — `$_SESSION['user_role']` must be `doctor` or `patient`. Unauthenticated users → redirect to login page.
2. **Appointment validity** — The appointment must exist in the database and its `status` must be `approved`. If it is `pending`, `cancelled`, `completed`, etc. → redirect to appointments list with a flash message.
3. **Ownership** — The logged-in user's ID must match either `doctor_id` or `patient_id` on the appointment. A doctor cannot join another doctor's call, and a patient cannot join someone else's appointment.

---

## 10. Common edits you might need to make

### Change token lifetime
Edit `STREAM_TOKEN_TTL_MINUTES` in `app/config/config.php`. No other files need changing.

### Allow joining slightly before the scheduled time
Add a time window check in `VideoCall::room()`:
```php
$nowUtc   = new DateTimeImmutable('now', new DateTimeZone('UTC'));
$startsAt = new DateTimeImmutable($apt->starts_at, new DateTimeZone('UTC'));
if ($nowUtc < $startsAt->modify('-10 minutes')) {
    $_SESSION['flash'] = 'The call is not open yet.';
    return redirect(...);
}
```

### Add screen sharing
In the videocall view JS, add:
```js
await streamCall.screenShare.enable();
```
And bind `streamCall.screenShare.state.mediaStream$` to a `<video>` element.

### Add a "complete appointment" action when the call ends
In the end-call handler in the videocall views, before the redirect:
```js
await fetch(`${URLROOT}/Appointments/setStatus/${APPOINTMENT_ID}/completed`);
```
Or do a form POST if you prefer.

### Mark appointment as completed when doctor ends call
In `v_doctor_videocall.php`, find the end call section:
```js
document.getElementById('endCallBtn').addEventListener('click', async () => {
    ...
    await streamCall.leave();
    // Add this line to auto-complete the appointment:
    window.location.href = `${BACK_URL.replace('doctor', '')}setStatus/${APPOINTMENT_ID}/completed`;
});
```

### Change where users are redirected after ending the call
- Doctor: find `const BACK_URL = '<?= URLROOT ?>/Appointments/doctor';` in `v_doctor_videocall.php`
- Patient: find `const BACK_URL = '<?= URLROOT ?>/Appointments/my';` in `v_patient_videocall.php`

### Update Stream SDK version
The SDK is imported via CDN:
```js
import { StreamVideoClient } from 'https://esm.sh/@stream-io/video-client@1';
```
Change `@1` to `@1.x.y` to pin to a specific version. Check [npm](https://www.npmjs.com/package/@stream-io/video-client) for the latest.

### Change your Stream credentials
Update `STREAM_API_KEY` and `STREAM_API_SECRET` in `app/config/config.php`. No other files reference these values directly.

---

## 11. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| `Undefined array key "appointment"` on precall page | Navigating to `/Pages/doctorPrecall` (old route) instead of `/VideoCall/precall/{id}` | Always use the "Start" / "Join Consultation" buttons in the appointments list |
| Black video boxes, no video | Browser blocked camera/mic permission | Check browser address bar for the camera permission prompt; allow it |
| `Could not connect to video call` alert | JWT rejected by Stream (clock skew, wrong secret, or expired token) | Check PHP server time is accurate; verify `STREAM_API_SECRET` in config |
| Doctor sees "Waiting for patient…" forever | Patient never navigated to the room URL | Both participants must visit `/VideoCall/room/{id}` |
| Patient gets "Appointment not found or not yet approved" | Appointment status is not `approved` yet | Doctor must approve the appointment first in `/Appointments/doctor` |
| Redirect loop on `/Pages/doctorPrecall` | Old bookmarks or links | Update any hardcoded links to use `/VideoCall/precall/{id}` |
| Call works but only one person's video shows | `remoteParticipants$` subscriber not receiving `videoStream` | The other participant may have their camera off; check console errors |

---

## File map summary

```
app/
├── config/
│   └── config.php                     ← MODIFIED: added 4 Stream constants
├── libraries/
│   └── StreamToken.php                ← NEW: server-side JWT generator
├── controllers/
│   ├── VideoCall.php                  ← NEW: precall + room actions for both roles
│   └── Pages.php                     ← MODIFIED: old precall methods now redirect
├── models/
│   └── Appointment.php               ← MODIFIED: added findWithNames() method
└── views/
    └── pages/
        ├── v_doctor_appointments.php  ← MODIFIED: "Start" button links to VideoCall/precall/{id}
        ├── v_patient_appointments.php ← MODIFIED: "Join" button links to VideoCall/precall/{id}
        └── Videoconsultation/
            ├── v_doctor_precall.php   ← MODIFIED: uses real appointment data
            ├── v_patient_precall.php  ← MODIFIED: uses real appointment data
            ├── v_doctor_videocall.php ← MODIFIED: real <video> elements + Stream SDK (create: true)
            └── v_patient_videocall.php← MODIFIED: real <video> elements + Stream SDK (create: false)
```
