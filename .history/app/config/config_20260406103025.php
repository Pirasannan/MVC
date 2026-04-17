<?php

    //Database configuration(connect db to framework)
    define('DB_HOST','localhost');
    define('DB_USER','root');
    define('DB_PASSWORD','');
    define('DB_NAME','mvc_db');



    //APP ROOT
    define('APPROOT', dirname(dirname(__FILE__)));

    //comment out the hardcoded URLROOT and use dynamic URLROOT instead
    //define('URLROOT','http://localhost/MVC' );
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('URLROOT', $scheme . '://' . $host . '/MVC');

    //WEBSITENAME
    define('SITENAME','MediLink');

    // GetStream.io Video credentials
    define('STREAM_API_KEY',            'rks96nwm9y8b');
    define('STREAM_API_SECRET',         'ez7m4fbuv7fzdbjwdm2g2znweaf6w8m3af79atywshun2xbyne2fbekpqxa3bdde');
    define('STREAM_CALL_TYPE',          'default');
    define('STREAM_TOKEN_TTL_MINUTES',  60);

    // Session configuration
    
// ini_set('session.cookie_lifetime', 0); // Session cookie expires when browser closes
// ini_set('session.gc_maxlifetime', 1440); // 24 minutes
// ini_set('session.cookie_httponly', 1); // Security
// ini_set('session.use_only_cookies', 1); // Security
    
?>