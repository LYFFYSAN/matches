<?php

// 1. Force the PHP environment to look inside your inner project folder
$projectRoot = __DIR__ . '/../worldcup-laravel';

// 2. Override the global server paths so Laravel knows exactly where the public assets sit
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = $projectRoot . '/public';

// 3. Boot Laravel using its real entry point
require $projectRoot . '/public/index.php';