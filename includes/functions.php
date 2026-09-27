<?php

/**
 * Membuat satu gambar akhir dari tiga foto dan satu template PNG transparan.
 */
function createPhotoStrip(array $photoFiles, string $templatePath, string $outputPath): bool
{
    if (count($photoFiles) !== 3 || !extension_loaded('gd')) {
        return false;
    }

    $photos = [];
    foreach ($photoFiles as $photoFile) {
        $image = @imagecreatefrompng($photoFile);
        if (!$image) {
            return false;
        }
        $photos[] = $image;
    }

    $template = file_exists($templatePath) ? @imagecreatefrompng($templatePath) : null;
    $width = $template ? imagesx($template) : 1200;
    $height = $template ? imagesy($template) : 1584;

    $canvas = imagecreatetruecolor($width, $height);
    $background = imagecolorallocate($canvas, 247, 242, 234);
    imagefill($canvas, 0, 0, $background);

    if ($template) {
        // These normalized slots match the three transparent boxes in the Birama template.
        $slots = [
            [.0778, .0570, .8522, .2037],
            [.0778, .2789, .8522, .2044],
            [.0778, .5015, .8522, .2037],
        ];
    } else {
        $slots = [
            [.35, .0303, .30, .303],
            [.35, .2525, .30, .303],
            [.35, .4747, .30, .303],
        ];
    }

    foreach ($photos as $index => $photo) {
        [$xRatio, $yRatio, $widthRatio, $heightRatio] = $slots[$index];
        $x = (int) round($width * $xRatio);
        $y = (int) round($height * $yRatio);
        $slotWidth = (int) round($width * $widthRatio);
        $slotHeight = (int) round($height * $heightRatio);
        $sourceWidth = imagesx($photo);
        $sourceHeight = imagesy($photo);
        $sourceRatio = $sourceWidth / $sourceHeight;
        $slotRatio = $slotWidth / $slotHeight;

        if ($sourceRatio > $slotRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $slotRatio);
            $sourceX = (int) round(($sourceWidth - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $slotRatio);
            $sourceX = 0;
            $sourceY = (int) round(($sourceHeight - $cropHeight) / 2);
        }

        imagecopyresampled($canvas, $photo, $x, $y, $sourceX, $sourceY, $slotWidth, $slotHeight, $cropWidth, $cropHeight);
    }

    // Template dibuat seukuran canvas agar elemen transparannya menutup seluruh strip.
    if ($template) {
        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);
        imagecopyresampled($canvas, $template, 0, 0, 0, 0, $width, $height, imagesx($template), imagesy($template));
        imagedestroy($template);
    }

    $saved = imagepng($canvas, $outputPath, 6);
    imagedestroy($canvas);
    foreach ($photos as $photo) {
        imagedestroy($photo);
    }

    return $saved;
}

function findTemplate(string $directory): ?string
{
    $templates = glob($directory . '/*.{png,PNG}', GLOB_BRACE);
    return $templates ? $templates[0] : null;
}
