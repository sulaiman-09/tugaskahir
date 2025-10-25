<?php
// Create small 1x1 PNG placeholder files for given filenames
$filenames = [
    '1760935557_lCk31NhD.png',
    '1760935586_vsWg58gZ.png',
    '1761219832_5jS0Gii1.png',
    '1761220602_dlt3xM3U.png',
    '1761221108_h0C7RWYV.png',
    '1761224126_rKcLND89.png',
    '1761224960_7kG0eruP.png',
];

$base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMBAQEA
AABJRU5ErkJggg=='; // 1x1 PNG
$bin = base64_decode($base64);
$dir = __DIR__ . '/../storage/app/public/ktp';
if (!is_dir($dir)) mkdir($dir, 0777, true);
foreach ($filenames as $f) {
    $path = $dir . '/' . $f;
    if (file_put_contents($path, $bin) !== false) {
        echo "created: $path\n";
    } else {
        echo "failed: $path\n";
    }
}
