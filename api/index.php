<?php

// Point explicitly to your subfolder path
$projectPath = __DIR__ . '/../worldcup-laravel';

if (file_exists($projectPath . '/public/index.php')) {
    require $projectPath . '/public/index.php';
} else {
    // Fallback directly to root if paths get flattened
    require __DIR__ . '/../public/index.php';
}