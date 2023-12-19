<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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

            // Generate thumbnail
            // $sourcePath = public_path() . '/temp/' . $newName;
            // $destPath = public_path() . '/temp/thumb/' . $newName;

            // $interventionImage = Image::make($sourcePath);
            // $interventionImage->fit(300, 300);
            // $interventionImage->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/' . $newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}
