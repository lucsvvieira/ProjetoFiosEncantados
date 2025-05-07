<?php

// Increase memory limit
ini_set('memory_limit', '512M');

$path = __DIR__ . '/storage/app/public/products';
$files = glob($path . '/*.jpg');

echo "Starting image optimization...\n";
$total = count($files);
$current = 0;

foreach ($files as $index => $file) {
    $current++;
    echo "Processing image {$current} of {$total}...\n";
    
    try {
        // Generate new filename
        $newName = 'produto-' . ($index + 1) . '.jpg';
        $newPath = $path . '/' . $newName;

        // Load image
        $image = imagecreatefromjpeg($file);
        
        if (!$image) {
            echo "Error loading image: {$file}\n";
            continue;
        }

        // Get image dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Calculate new dimensions if needed
        if ($width > 1200) {
            $newWidth = 1200;
            $newHeight = ($height / $width) * $newWidth;

            // Create new image
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            if (!$newImage) {
                echo "Error creating new image for: {$file}\n";
                imagedestroy($image);
                continue;
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Save optimized image
            imagejpeg($newImage, $newPath, 80);
            imagedestroy($newImage);
        } else {
            // Save with compression
            imagejpeg($image, $newPath, 80);
        }

        imagedestroy($image);

        // Delete original file if it's different from the new path
        if ($file !== $newPath && file_exists($file)) {
            unlink($file);
        }

        // Force garbage collection
        gc_collect_cycles();
        
    } catch (Exception $e) {
        echo "Error processing {$file}: " . $e->getMessage() . "\n";
    }
}

echo "\nImages optimized and renamed successfully!\n"; 