<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Log;

class CkeditorController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('CKEditor upload request', [
            'has_file' => $request->hasFile('upload'),
            'all_files' => $request->allFiles(),
            'has_token' => $request->has('_token'),
            'method' => $request->method()
        ]);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        try {
            $request->validate([
                'upload' => 'required|file|mimes:jpg,jpeg,png,webp,avif,gif|max:5120'
            ]);
            $imageFile = $request->file('upload');
            if (!$imageFile->isValid()) {
                throw new \Exception(
                    'Invalid file upload. Error: ' . $imageFile->getError()
                );
            }
            $originalName = pathinfo(
                $imageFile->getClientOriginalName(),
                PATHINFO_FILENAME
            );
            $fileName = ImageHelper::generateFileName($originalName);
            ImageHelper::uploadSingleImageWebpOnly(
                $imageFile,
                $fileName,
                'ckeditor'
            );
            $finalFileName = $fileName . '.webp';
            $url = asset('storage/images/ckeditor/' . $finalFileName);
            Log::info('CKEditor upload success', [
                'file' => $finalFileName,
                'url' => $url
            ]);
            if ($CKEditorFuncNum) {
                return response(
                    "<script>
                        window.parent.CKEDITOR.tools.callFunction(
                            {$CKEditorFuncNum},
                            '{$url}',
                            'Image uploaded successfully'
                        );
                    </script>"
                );
            }
            return response()->json([
                'uploaded' => true,
                'fileName' => $finalFileName,
                'url' => $url
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessage = collect($e->errors())
                ->flatten()
                ->first();
            Log::error('CKEditor validation failed: ' . $errorMessage);
            if ($CKEditorFuncNum) {
                return response(
                    "<script>
                        window.parent.CKEDITOR.tools.callFunction(
                            {$CKEditorFuncNum},
                            '',
                            '{$errorMessage}'
                        );
                    </script>"
                );
            }

            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => $errorMessage
                ]
            ], 422);
        } catch (\Exception $e) {
            Log::error('CKEditor upload failed: ' . $e->getMessage());
            $errorMessage = 'Upload failed: ' . $e->getMessage();
            if ($CKEditorFuncNum) {
                return response(
                    "<script>
                        window.parent.CKEDITOR.tools.callFunction(
                            {$CKEditorFuncNum},
                            '',
                            '{$errorMessage}'
                        );
                    </script>"
                );
            }
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => $errorMessage
                ]
            ], 500);
        }
    }

    public function imageList(Request $request)
    {
        try {
            $imagePath = storage_path('app/public/images/ckeditor/');
            if (!File::exists($imagePath)) {
                return response()->json([
                    'images' => [],
                    'hasMore' => false
                ]);
            }
            $page = (int) $request->get('page', 1);
            $limit = 30;
            $files = collect(File::files($imagePath))
                ->sortByDesc(function ($file) {
                    return $file->getMTime();
                })
                ->values();
            $total = $files->count();
            $paginatedFiles = $files
                ->slice(($page - 1) * $limit, $limit)
                ->values();
            $images = [];
            foreach ($paginatedFiles as $file) {
                $images[] = [
                    'url' => asset('storage/images/ckeditor/' . $file->getFilename()),
                    'name' => $file->getFilename(),
                ];
            }
            return response()->json([
                'images' => $images,
                'hasMore' => (($page * $limit) < $total)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'images' => [],
                'hasMore' => false
            ]);
        }
    }
    /**
     * Optional: Delete CKEditor image
     */
    public function deleteImage(Request $request)
    {
        try {
            $imageName = $request->input('image');
            if (empty($imageName)) {
                return response()->json(['error' => 'Image name required'], 400);
            }
            if (strpos($imageName, '..') !== false || strpos($imageName, '/') !== false) {
                return response()->json(['error' => 'Invalid image name'], 400);
            }
            Log::info('Attempting to delete image', ['image' => $imageName]);
            $deleted = ImageHelper::deleteSingleImage($imageName, 'ckeditor');
            if ($deleted) {
                Log::info('Image deleted successfully', ['image' => $imageName]);
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            } else {
                Log::warning('Image not found for deletion', ['image' => $imageName]);
                return response()->json([
                    'error' => 'Image not found'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('CKEditor delete failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
