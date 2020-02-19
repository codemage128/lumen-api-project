<?php


namespace App;


class Utility
{
    public static function getProductMasterFileName($sku_no, $product_title){
        $filename = $sku_no. "---".str_replace(' ', '-', $product_title).'.jpg';
        return $filename;
    }

    public static function getProductStickerFileName($sku_no, $product_title, $notepad){
        $sticker_width = Product::where('product_title', $product_title)->first()->stickers_width;
        $sticker_height = Product::where('product_title', $product_title)->first()->stickers_height;
        $filename = $sku_no. "---".str_replace(' ', '-', $product_title ).'-'. $sticker_width .' x '. $sticker_height .'-'. $notepad.'.jpg';
        return $filename;
    }
    public static function getProductVariantFileName($sku_no, $product_title, $variant_color){
        $filename = $sku_no.'---'.str_replace(' ', '-', trim($product_title).'-'.str_replace('', '-', $variant_color).'.jpg');
        return $filename;
    }
    public static function getFileName($fileList){
        $count = count($fileList);
        $fileNameList = array();
        for($i = 0; $i < $count; $i ++){
            $urlstring = $fileList[$i];
            $string  = str_replace('D:\react\skuapp\skuapp_backend/public/excels/', "Excel/", $urlstring);
            array_push($fileNameList, trim($string));
        }
        return $fileNameList;
    }
}
