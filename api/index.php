<?php

// Force error reporting to show us the raw exception right on the screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Adjust paths if your Laravel files live inside a subdirectory
// (Change 'worldcup-laravel' below if your folder name is spelled differently)
$subDir = '/../worldcup-laravel'; 

if (file_exists(__DIR__ . $subDir . '/public/index.php')) {
    require __DIR__ . $subDir . '/public/index.php';
} else {
    // Fallback to top-level if files are right at the root
    require __DIR__ . '/../public/index.php';
}