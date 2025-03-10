<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function index()
    {
        $file = File::allFiles(public_path('images'))[0] ?? null;
        if ($file) {
            return view('main', ['file' => env('APP_URL') . '/images/' . $file->getFilename()]);
        }

        return view('main', ['file' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = 'qrcode' . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        return view('main', ['file' => env('APP_URL') . '/images/' . $imageName]);
    }

    public function destroy()
    {
        $file = File::allFiles(public_path('images'))[0] ?? null;
        if ($file) {
            File::delete($file->getPathname());
        }

        return redirect('/');
    }
}
