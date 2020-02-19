<?php


namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Validator;

class SettingController extends ApiController
{
    public function get(Request $request){
        $setting = Setting::first();
        return $this->success($setting);
    }

    public function update(Request $request)
    {
        if($request->type == 'weight') {
            $validator = Validator::make($request->all(), [
                    'value' => 'required'
                ]
            );
        }
        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        $setting = Setting::first();
        $value = $request->value;
        $type = $request->type;

        switch ($type) {
            case 'sku_number':
                $setting->sku_number = $value;
                break;
            case 'brand_name':
                $setting->brand = $value;
                break;
            case 'dropbox_root':
                $setting->dropbox_root = $value;
                break;
            case 'weight':
                $setting->weight = $value;
                break;
            default:
                break;
        }
        $setting->save();
        return $this->success(['setting' => $setting, 'message' => "Setting is updated successfully!"]);
    }
}
