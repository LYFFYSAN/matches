<?php

// 1. Override the global server paths for the root directory execution
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../public';

// 2. Boot Laravel using the real root level public directory
require __DIR__ . '/../public/index.php';