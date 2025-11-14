<?php

$routeFiles = [
    'auth.php',
    'dashboard.php',
    'policies.php',
    'admin.php',
    'forms.php',
    'activities.php',
    'teams.php',
];

foreach ($routeFiles as $file) {
    try {
        require __DIR__ . '/' . $file;
    } catch (\Throwable $e) {
        Log::error("Error loading route file {$file}: " . $e->getMessage());
        // Optional: continue loading other routes
    }
}



