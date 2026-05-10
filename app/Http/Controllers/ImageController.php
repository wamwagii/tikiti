<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function show($path)
    {
        // Try different path variations
        $pathsToTry = [
            'public/' . $path,
            $path,
            'events/' . basename($path),
            'public/events/' . basename($path),
        ];
        
        foreach ($pathsToTry as $tryPath) {
            if (Storage::exists($tryPath)) {
                try {
                    $file = Storage::get($tryPath);
                    $mimeType = $this->getMimeType($tryPath);
                    
                    // Return the image with proper headers
                    return response($file, 200)
                        ->header('Content-Type', $mimeType)
                        ->header('Content-Length', strlen($file))
                        ->header('Cache-Control', 'public, max-age=31536000')
                        ->header('Access-Control-Allow-Origin', '*');
                } catch (\Exception $e) {
                    Log::error('Error reading file: ' . $e->getMessage());
                    continue;
                }
            }
        }
        
        // File not found - log the attempt
        Log::warning('Image not found', ['path' => $path, 'tried_paths' => $pathsToTry]);
        
        // Return a fallback image (a simple SVG placeholder)
        return response($this->getPlaceholderImage(), 200)
            ->header('Content-Type', 'image/svg+xml');
    }
    
    private function getMimeType($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };
    }
    
    private function getPlaceholderImage()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
            <rect width="400" height="300" fill="#e0e7ff"/>
            <text x="200" y="150" font-family="Arial" font-size="24" fill="#4648d4" text-anchor="middle">🎫 No Image</text>
        </svg>';
    }
}