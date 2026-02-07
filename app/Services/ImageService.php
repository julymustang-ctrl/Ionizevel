<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Görsel İşleme Servisi
 * 
 * Watermark (filigran) ekleme ve görsel manipülasyonu
 */
class ImageService
{
    protected $manager;

    public function __construct()
    {
        // GD driver kullan (imagick da olabilir)
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Görsele watermark ekle
     *
     * @param string $imagePath Kaynak görsel yolu
     * @param string $watermarkPath Watermark görsel yolu
     * @param string $position Pozisyon: bottom-right, bottom-left, top-right, top-left, center
     * @param int $opacity Opaklık (0-100)
     * @return bool
     */
    public function addWatermark(
        string $imagePath,
        string $watermarkPath,
        string $position = 'bottom-right',
        int $opacity = 50
    ): bool {
        try {
            if (!file_exists($imagePath) || !file_exists($watermarkPath)) {
                return false;
            }

            $image = $this->manager->read($imagePath);
            $watermark = $this->manager->read($watermarkPath);

            // Watermark boyutunu ayarla (orijinal görselin %20'si)
            $maxWidth = $image->width() * 0.2;
            $maxHeight = $image->height() * 0.2;
            
            if ($watermark->width() > $maxWidth || $watermark->height() > $maxHeight) {
                $watermark->scaleDown(width: (int)$maxWidth, height: (int)$maxHeight);
            }

            // Pozisyon hesapla
            $coords = $this->calculatePosition($image, $watermark, $position);

            // Watermark'ı uygula
            $image->place($watermark, 'top-left', $coords['x'], $coords['y'], $opacity);

            // Kaydet
            $image->save($imagePath);

            return true;
        } catch (\Exception $e) {
            \Log::error('Watermark error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Metin watermark ekle
     */
    public function addTextWatermark(
        string $imagePath,
        string $text,
        string $position = 'bottom-right',
        int $fontSize = 24,
        string $color = '#ffffff',
        int $opacity = 70
    ): bool {
        try {
            if (!file_exists($imagePath)) {
                return false;
            }

            $image = $this->manager->read($imagePath);

            // Pozisyon hesapla
            $x = $position === 'bottom-right' || $position === 'top-right' 
                ? $image->width() - 20 
                : 20;
            $y = $position === 'bottom-right' || $position === 'bottom-left' 
                ? $image->height() - 20 
                : 30;
            
            $align = str_contains($position, 'right') ? 'right' : 'left';
            $valign = str_contains($position, 'bottom') ? 'bottom' : 'top';

            $image->text($text, $x, $y, function ($font) use ($fontSize, $color) {
                $font->size($fontSize);
                $font->color($color);
            });

            $image->save($imagePath);

            return true;
        } catch (\Exception $e) {
            \Log::error('Text watermark error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Pozisyon hesapla
     */
    protected function calculatePosition($image, $watermark, string $position): array
    {
        $padding = 10;
        
        switch ($position) {
            case 'top-left':
                return ['x' => $padding, 'y' => $padding];
            case 'top-right':
                return ['x' => $image->width() - $watermark->width() - $padding, 'y' => $padding];
            case 'bottom-left':
                return ['x' => $padding, 'y' => $image->height() - $watermark->height() - $padding];
            case 'bottom-right':
                return [
                    'x' => $image->width() - $watermark->width() - $padding,
                    'y' => $image->height() - $watermark->height() - $padding
                ];
            case 'center':
                return [
                    'x' => ($image->width() - $watermark->width()) / 2,
                    'y' => ($image->height() - $watermark->height()) / 2
                ];
            default:
                return ['x' => $image->width() - $watermark->width() - $padding, 'y' => $image->height() - $watermark->height() - $padding];
        }
    }

    /**
     * Toplu watermark uygula
     */
    public function applyWatermarkToFolder(
        string $folderPath,
        string $watermarkPath,
        string $position = 'bottom-right',
        int $opacity = 50
    ): array {
        $results = ['success' => 0, 'failed' => 0];
        
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        foreach (glob($folderPath . '/*') as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $extensions)) {
                if ($this->addWatermark($file, $watermarkPath, $position, $opacity)) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                }
            }
        }

        return $results;
    }
}
