<?php


namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    protected $master_Type = ['T', 'S', 'M', 'B', 'C', 'H', 'K'];
    protected $master_type = ['tshirts', 'stickers', 'mugs', 'toteBags', 'cushionCovers', 'hoodies', 'kids'];

    public function masters()
    {
        return $this->hasMany('App\ProductMaster', 'product', 'id');
    }

    public function categoryModel()
    {
        return $this->belongsTo('App\Category', 'category', 'id');
    }

    public function keywordModel()
    {
        return $this->belongsTo('App\Keyword', 'keyword', 'id');
    }


    public function artistModel()
    {
        return $this->belongsTo('App\Artist', 'artist', 'id');
    }

    public function copySkuImage($i, $sku_number_master)
    {
        $productfilepath = ProductImageSet::where(['master' => $this->master_type[$i]])->where(['setname' => $this->tshirt_image])->first();
        $productPath1 = $productfilepath->file1;
        $productPath2 = $productfilepath->file2;
        $productPath3 = $productfilepath->file3;
        switch ($i) {
            case 0: //tshirts
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $this->master_Type[$i] . "MP" . $this->product_no . 'MAS-'
                        . $this->artistModel->code . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath3 != "") {
                    $dropboxpath3 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $this->master_Type[$i] . "FP" . $this->product_no . 'MAS-'
                        . $this->artistModel->code . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath3, $dropboxpath3);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 1: //stickers
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }

                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 2: //mugs
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $this->master_Type[$i] . "SG" . $this->product_no . 'MAS-'
                        . $this->artistModel->code . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath3 != "") {
                    $dropboxpath3 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $this->master_Type[$i] . "XG" . $this->product_no . 'MAS-'
                        . $this->artistModel->code . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath3, $dropboxpath3);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 3: //toteBags
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 4: //cushionCovers
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $this->master_Type[$i] . "QP" . $this->product_no . 'MAS-'
                        . $this->artistModel->code . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath3 != "") {
                    $dropboxpath3 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $this->master_Type[$i] . "NP" . $this->product_no . 'MAS-'
                        . $this->artistModel->code . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath3, $dropboxpath3);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 5: //hoodies
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            case 6: //kids
                if ($productPath1 != "") {
                    $dropboxpath1 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.ai';
                    try {
                        DropboxUtility::copyFile($productPath1, $dropboxpath1);
                    } catch (\Exception $exception) {
                    }
                }
                if ($productPath2 != "") {
                    $dropboxpath2 = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/" . $sku_number_master . '-' . $this->product_title . '.psd';
                    try {
                        DropboxUtility::copyFile($productPath2, $dropboxpath2);
                    } catch (\Exception $exception) {
                    }
                }
                break;
            default:
                break;
        }
    }
    public function creatProductMaster()
    {
        /* master sku_number generate based on sku only number*/
        for ($i = 0; $i < count($this->master_type); $i++) {
            $sku_number_master = $this->getMasterSkuNumber($i);
            $this->copySkuImage($i, $sku_number_master);
            $master_image = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 1);
            $sticker_image1 = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 2);
            $sticker_image2 = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 3);
            $sticker_image3 = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 4);
            $sticker_image4 = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 5);
            $sticker_image5 = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 6);
            $sticker_image6 = $this->createMasterDirectoryAndImagePath($i, $sku_number_master, 7);

            if ($i == 1) {  //sticker
                ProductMaster::create([
                    'product' => $this->id,
                    'sku_no' => $sku_number_master,
                    "image_primary" => $master_image,
                    'image1' => $sticker_image1,
                    'image2' => $sticker_image2,
                    'image3' => $sticker_image3,
                    'image4' => $sticker_image4,
                    'image5' => $sticker_image5,
                    'image6' => $sticker_image6,
                    'master_type' => $this->master_type[$i],
                    'is_upload' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {//others
                $variant_image = VariantImage::where(['master' => $this->master_type[$i]])->first();
                $productMaster = ProductMaster::create([
                    'product' => $this->id,
                    'sku_no' => $sku_number_master,
                    "image_primary" => $master_image,
                    'image1' => $variant_image->image1,
                    'image2' => $variant_image->image2,
                    'image3' => $variant_image->image3,
                    'image4' => $variant_image->image4,
                    'image5' => $variant_image->image5,
                    'image6' => $variant_image->image6,
                    'master_type' => $this->master_type[$i],
                    'is_upload' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $productMaster->createVariants();
            }
        }
    }

    public
    function getMasterSkuNumber($i)
    {
        $artist = $this->artistModel->code;
        switch ($i) {
            case 0: //Tshirts
                return $this->master_Type[$i] . "U" . $this->tshirt_print_mode . $this->product_no . "MAS-" . $artist;
            case 1: //stickers
                return $this->master_Type[$i] . "C0" . $this->product_no . $this->stickers_type . '-' . $artist;
            case 2: //mugs
                return $this->master_Type[$i] . "UG" . $this->product_no . "MAS-" . $artist;
            default:
                return $this->master_Type[$i] . "U". $this->tshirt_print_mode . $this->product_no . "MAS-" . $artist;
        }
    }


    public
    function createMasterDirectoryAndImagePath($i, $sku_number_master, $notepad)
    {
        $master_image_directory = Setting::first()->dropbox_root . "/" . ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/Images/";
        DropboxUtility::createDirectory($master_image_directory);

        if ($this->master_type[$i] == 'stickers') {
            return ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/Images/" . Utility::getProductStickerFileName($sku_number_master, $this->product_title, $notepad);
        } else {
            return ucfirst($this->master_type[$i]) . "/" . $sku_number_master . "-" . $this->product_title . "/Images/" . Utility::getProductMasterFileName($sku_number_master, $this->product_title);
        }
    }
}
