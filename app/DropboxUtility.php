<?php


namespace App;

use Illuminate\Support\Facades\Storage;

class DropboxUtility
{
    public static function loadFile(string $fullpath, string $filename){
        $data = Storage::disk('dropbox')->get($fullpath);
        $img = base64_encode($data);
        Storage::disk('public')->put($filename, $data);
//        $path = url('/dropbox/'.$filename);
        return $img;
    }
    /*Copy the file from oldpath to newpath*/
    public static function copyFile(string $oldPath, string $newPath){
        //$data = Storage::disk('dropbox')->copy('bblog1.jpg', 'bblog_1.jpg');
        $data = Storage::disk('dropbox')->get($oldPath);
        Storage::disk('dropbox')->put($newPath, $data);
    }
    /*create the drectory at dropbox*/
    public static function createDirectory(string $fullString){
        $data = Storage::disk('dropbox')->makeDirectory($fullString);
    }
}
