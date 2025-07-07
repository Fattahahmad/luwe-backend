<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    /**
     * Serve image files
     */
    public function show($filename)
    {
        // Path to profiles directory
        $path = public_path('images/profiles/' . $filename);
        
        // Check if file exists
        if (!file_exists($path)) {
            // Return default image if file not found
            $path = public_path('images/profiles/default.svg');
        }
        
        // Get file extension
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        // Set content type based on extension
        $contentType = match($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => 'image/jpeg'
        };
        
        // Return image with proper headers
        return response()->file($path, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
        ]);
    }
}
