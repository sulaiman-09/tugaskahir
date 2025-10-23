<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\SudirmanTowerAddress;
$h = SudirmanTowerAddress::latest()->first();
if ($h) echo $h->id . PHP_EOL; else echo 'NONE' . PHP_EOL;
