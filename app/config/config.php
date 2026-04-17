<?php

//Database configuration(connect db to framework)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mvc_db');



//APP ROOT
define('APPROOT', dirname(dirname(__FILE__)));

    //comment out the hardcoded URLROOT and use dynamic URLROOT instead
    //define('URLROOT','http://localhost/MVC' );
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('URLROOT', $scheme . '://' . $host . '/MVC');
    //comment out the hardcoded URLROOT and use dynamic URLROOT instead
    //define('URLROOT','http://localhost/MVC' );
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('URLROOT', $scheme . '://' . $host . '/MVC');

//WEBSITENAME
define('SITENAME', 'MediLink');

// GetStream.io Video credentials
define('STREAM_API_KEY', 'rks96nwm9y8b');
define('STREAM_API_SECRET', 'ez7m4fbuv7fzdbjwdm2g2znweaf6w8m3af79atywshun2xbyne2fbekpqxa3bdde');
define('STREAM_CALL_TYPE', 'default');
define('STREAM_TOKEN_TTL_MINUTES', 60);

// Session configuration

// ini_set('session.cookie_lifetime', 0); // Session cookie expires when browser closes
// ini_set('session.gc_maxlifetime', 1440); // 24 minutes
// ini_set('session.cookie_httponly', 1); // Security
// ini_set('session.use_only_cookies', 1); // Security

// ── Mail Configuration (PHPMailer via Gmail SMTP) ──────────────────────
// 1. Enable 2-Step Verification on your Google account
// 2. Go to: myaccount.google.com → Security → App Passwords
// 3. Create an App Password for "Mail" → paste the 16-char code below
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '2023cs146@stu.ucsc.cmb.ac.lk');   // ← Replace with your Gmail
define('MAIL_PASSWORD', 'qqqe kpsx dvgw vxsb');    // ← Replace with your App Password
define('MAIL_FROM', '2023cs146@stu.ucsc.cmb.ac.lk');   // ← Same Gmail usually
define('MAIL_FROM_NAME', 'MediLink');
// ────────────────────────────────────────────────────────────────────────

?>