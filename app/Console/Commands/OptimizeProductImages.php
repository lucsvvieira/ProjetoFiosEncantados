<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeProductImages extends Command
{
    protected $signature = 'images:optimize';
    protected $description = 'Optimize and rename product images';

    public function handle()
    {
        $path = storage_path('app/public/products');
        $files = glob($path . '/*.jpg');

        $this->info('Starting image optimization...');
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $index => $file) {
            // Generate new filename
            $newName = 'produto-' . ($index + 1) . '.jpg';
            $newPath = $path . '/' . $newName;

            // Load image
            $image = imagecreatefromjpeg($file);
            
            // Get image dimensions
            $width = imagesx($image);
            $height = imagesy($image);

            // Calculate new dimensions if needed
            if ($width > 1200) {
                $newWidth = 1200;
                $newHeight = ($height / $width) * $newWidth;

                // Create new image
                $newImage = imagecreatetruecolor($newWidth, $newHeight);
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
            if ($file !== $newPath) {
                unlink($file);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nImages optimized and renamed successfully!");
    }
} 