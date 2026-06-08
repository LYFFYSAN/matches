<?php

// 1. Load the normal Laravel bootstrap sequence
require __DIR__ . '/../public/index.php';

// 2. Automatically run migrations in-memory if they haven't run yet
try {
    \Illuminate\Support\Facades\Artisan::call('migrate', [
        '--force' => true,
    ]);
    
    // Optional: If you have a database seeder with match summaries, uncomment the line below!
    // \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
    
} catch (\Exception $e) {
    // Prevent migration boot errors from locking the app loop
    unset($e);
}