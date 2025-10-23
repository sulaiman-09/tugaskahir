<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SudirmanPark;
use Illuminate\Support\Facades\Storage;

$customers = SudirmanPark::select('id','name','ktp')->whereNotNull('ktp')->get();
if ($customers->isEmpty()) {
    echo "No customers with ktp found\n";
    exit;
}

$disk = Storage::disk('public');
foreach ($customers as $c) {
    $path = 'ktp/' . $c->ktp;
    $exists = $disk->exists($path) ? 'YES' : 'NO';
    echo "ID: {$c->id} | name: {$c->name} | ktp: {$c->ktp} | exists: {$exists} | path: {$path}\n";
}
