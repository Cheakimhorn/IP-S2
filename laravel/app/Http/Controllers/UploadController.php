<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // 1. Validate request
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $file = $request->file('document');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // 2. Store on MinIO
            $minioPath = $file->storeAs('uploads', $fileName, 'minio');
            $minioUrl = rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/' . $minioPath;

            // 3. Store locally (public disk)
            $localPath = $file->storeAs('uploads', $fileName, 'public');

            // 4. Create thumbnail
            $thumbnailImage = Image::make($file->getRealPath());
            $thumbnailImage->fit(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });

            $thumbnailFileName = 'thumb_' . $fileName;
            $thumbnailPath = 'thumbnails/' . $thumbnailFileName;

            // 5. Save thumbnail to both local and MinIO
            $encodedThumbnail = (string) $thumbnailImage->encode();

            Storage::disk('public')->put($thumbnailPath, $encodedThumbnail);
            Storage::disk('minio')->put($thumbnailPath, $encodedThumbnail);

            $minioThumbUrl = rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/' . $thumbnailPath;

            // 6. Return response
            return response()->json([
                'minio_path'      => $minioPath,
                'minio_url'       => $minioUrl,
                'local_path'      => $localPath,
                'thumbnail_path'  => $thumbnailPath,
                'thumbnail_url'   => $minioThumbUrl,
            ], 201);

        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed.'], 500);
        }
    }
}
