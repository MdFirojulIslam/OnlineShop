<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\File;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json([
                'status' => false,
                'message' => 'No image file found.'
            ]);
        }

        $image = $request->file('image'); // Get file instance
        $ext = $image->getClientOriginalExtension();
        $newName = time() . '.' . $ext;

        $tempPath = public_path('temp'); // Temp folder path
        $thumbPath = public_path('temp/thumb'); // Thumbnail folder path

        // Ensure directories exist
        if (!File::exists($thumbPath)) {
            File::makeDirectory($thumbPath, 0777, true, true);
        }

        $image->move($tempPath, $newName); // Move original image

        // Create thumbnail
        $sourcePath = $tempPath . '/' . $newName;
        $thumbFullPath = $thumbPath . '/' . $newName;

        $manager = new ImageManager(new GdDriver()); 
        $img = $manager->read($sourcePath)->cover(300, 275);
        $img->save($thumbFullPath);

        // Save image record in database
        $tempImage = new TempImage();
        $tempImage->name = $newName;
        $tempImage->save();

        return response()->json([
            'status' => true,
            'image_id' => $tempImage->id,
            'ImagePath' => asset('temp/thumb/' . $newName),
            'message' => 'Image uploaded successfully'
        ]);
    }
}
