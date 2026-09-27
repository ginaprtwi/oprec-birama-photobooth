<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['email']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['error' => 'Sesi tidak valid.']);
    exit;
}

require __DIR__ . '/includes/functions.php';

$payload = json_decode(file_get_contents('php://input'), true);
$photos = $payload['photos'] ?? [];
if (count($photos) !== 3) {
    http_response_code(422);
    echo json_encode(['error' => 'Tiga foto diperlukan.']);
    exit;
}

$sessionId = bin2hex(random_bytes(8));
$photoFiles = [];
foreach ($photos as $index => $photoData) {
    if (!preg_match('/^data:image\/png;base64,/', $photoData)) {
        http_response_code(422);
        echo json_encode(['error' => 'Format foto tidak valid.']);
        exit;
    }
    $binary = base64_decode(substr($photoData, strpos($photoData, ',') + 1), true);
    if ($binary === false) {
        http_response_code(422);
        echo json_encode(['error' => 'Foto tidak dapat dibaca.']);
        exit;
    }
    $file = __DIR__ . '/uploads/' . $sessionId . '-photo-' . ($index + 1) . '.png';
    file_put_contents($file, $binary);
    $photoFiles[] = $file;
}

$outputName = $sessionId . '-result.png';
$outputPath = __DIR__ . '/uploads/' . $outputName;
$templatePath = findTemplate(__DIR__ . '/assets/templates');
if (!createPhotoStrip($photoFiles, $templatePath ?? '', $outputPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'GD belum aktif atau gambar gagal dibuat.']);
    exit;
}

$_SESSION['photos'] = $photoFiles;
$_SESSION['result_file'] = $outputName;
echo json_encode(['success' => true]);
