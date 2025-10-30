<?php

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\SudirmanPark;
use Illuminate\Support\Facades\Storage;

header('Content-Type: application/json');

// Get customer ID from query string
$customerId = $_GET['id'] ?? null;

if (!$customerId) {
    echo json_encode(['error' => 'Please provide a customer ID, e.g., ?id=1']);
    exit;
}

try {
    $customer = SudirmanPark::find($customerId);

    if (!$customer) {
        echo json_encode([
            'error' => 'Customer not found',
            'customerId' => $customerId
        ]);
        exit;
    }

    $disk = Storage::disk('public');
    $filename = $customer->ktp;
    $relativePath = 'ktp/' . $filename;
    $absolutePath = $disk->path($relativePath);
    $exists = $disk->exists($relativePath);
    $url = $disk->url($relativePath);
    $permissions = substr(sprintf('%o', fileperms($disk->path('ktp'))), -4);


    $directoryListing = [];
    try {
        $files = $disk->files('ktp');
        foreach ($files as $file) {
            $directoryListing[] = $file;
        }
    } catch (\Exception $e) {
        $directoryListing[] = "Error listing directory: " . $e->getMessage();
    }

    echo json_encode([
        'message' => 'KTP File Debug Information',
        'customerId' => $customerId,
        'filenameFromDb' => $filename,
        'relativePathChecked' => $relativePath,
        'absolutePathChecked' => $absolutePath,
        'fileExists' => $exists,
        'generatedUrl' => $url,
        'ktp_directory_permissions' => $permissions,
        'directoryListing_ktp' => $directoryListing,
    ], JSON_PRETTY_PRINT);

} catch (\Exception $e) {
    echo json_encode([
        'error' => 'An exception occurred',
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
