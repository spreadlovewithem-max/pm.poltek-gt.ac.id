<?php
/**
 * CONTOH konfigurasi database — AMAN untuk di-commit ke repository
 *
 * ┌─────────────────────────────────────────────────────────────┐
 * │  CARA PAKAI:                                                │
 * │  1. Salin file ini:  cp config.example.php config.php       │
 * │  2. Isi config.php dengan kredensial server Anda            │
 * │  3. Jangan pernah commit config.php ke repository!          │
 * └─────────────────────────────────────────────────────────────┘
 */

define('DB_HOST',    'localhost');
define('DB_NAME',    'nama_database_anda');
define('DB_USER',    'username_database');
define('DB_PASS',    'password_database');
define('DB_CHARSET', 'utf8');
