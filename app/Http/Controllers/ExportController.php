<?php


namespace App\Http\Controllers;


use App\ProductMaster;
use App\Utility;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportCSV(Request $request)
    {
        $uploadData = json_decode($request->data);
        $uploadDataCount = count($uploadData);
        $fileList = array();
        $zip = new \ZipArchive();
        $zip_name = "sku.zip";
        if ($zip->open($zip_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            exit("cannot open <$zip_name>\n");
        }
        if ($uploadDataCount == 0) {
            $zip->close();
            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=$zip_name");
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile("$zip_name");
            return response()->download($zip_name);
        } else {
            for ($i = 0; $i < $uploadDataCount; $i++) {
                $productMaster = ProductMaster::where(['sku_no' => $uploadData[$i]])->first();
                $productMaster->is_upload = 1;
                $productMaster->save();
                $file = $productMaster->createExcelMaster();
                array_push($fileList, $file);
            }
            $filenameList = Utility::getFileName($fileList);
            for ($i = 0; $i < count($fileList); $i++) {
                $zip->addFile($fileList[$i], $filenameList[$i]);
            }
            $zip->close();
            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=$zip_name");
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile("$zip_name");
            return response()->download($zip_name);
        }
    }
}
