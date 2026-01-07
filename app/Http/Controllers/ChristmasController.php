<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ChristmasController extends Controller
{
  public function getImages()
    {
        $path = public_path('img/Christmas');
        $files = File::files($path);

        $images = array_map(fn($file) => asset('img/Christmas/' . $file->getFilename()), $files);

        return response()->json($images); // return as JSON
    }
}
