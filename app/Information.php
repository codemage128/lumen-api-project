<?php


namespace App;


class Information
{
    public static function getstickerType(){
        return [
            ['key' => 'CUS', 'name' => 'Custom'],
            ['key' => 'ACC', 'name' => 'Accessories'],
            ['key' => 'VCC', 'name' => 'Sticker Print Mode'],
            ['key' => 'WHT', 'name' => 'Sticker Print Mode'],
            ['key' => 'WHX', 'name' => 'Sticker Print Mode'],
            ['key' => 'W02', 'name' => 'Sticker Print Mode'],
            ['key' => 'W03', 'name' => 'Sticker Print Mode'],
            ['key' => 'W04', 'name' => 'Sticker Print Mode'],
            ['key' => 'W05', 'name' => 'Sticker Print Mode'],
            ['key' => 'W06', 'name' => 'Sticker Print Mode'],
            ['key' => 'W07', 'name' => 'Sticker Print Mode'],
            ['key' => 'W08', 'name' => 'Sticker Print Mode'],
            ['key' => 'W09', 'name' => 'Sticker Print Mode'],
            ['key' => 'W010', 'name' => 'Sticker Print Mode'],
            ['key' => 'W011', 'name' => 'Sticker Print Mode'],
            ['key' => 'W012', 'name' => 'Sticker Print Mode']
        ];
    }
    public static function getPrintMode(){
        return [
            ['key' => 'P', 'name' => 'Print'],
            ['key' => 'V', 'name' => 'Vinyl'],
        ];
    }
    public static function getImageType(){
        return [
            ['key'=> '1', 'name' => 'set1'],
            ['key'=> '2', 'name' => 'set2'],
            ['key'=> '3', 'name' => 'set3']
        ];
    }
}
