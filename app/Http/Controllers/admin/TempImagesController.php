<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
//use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Gd\Driver;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $uploadedImage = $request->image;

        if (!empty($uploadedImage)) {
            $ext = $uploadedImage->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $uploadedImage->move(public_path() . '/temp', $newName);
            $manager = new ImageManager(Driver::class);
            // Generate thumbnail
             $sourcePath = public_path() . '/temp/' . $newName;
             $destPath = public_path() . '/temp/thumb/' . $newName;

            // $interventionImage = Image::make($sourcePath);
            // $interventionImage->fit(300, 300);
            // $interventionImage->save($destPath);
            $image = $manager->read($sourcePath);
            $image = $image->Resize(300,300);
            $image->save($destPath);
            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/' . $newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}
