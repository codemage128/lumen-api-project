<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;
use Auth;

class DropboxController extends Controller
{
    public function getImageFile(Request $request)
    {
        $path = Setting::first()->dropbox_root . "/" . $request->fileName . '.jpg';
        $data = Storage::disk('dropbox')->get($path);
        $img = base64_encode($data);
        return $data;
    }
}
