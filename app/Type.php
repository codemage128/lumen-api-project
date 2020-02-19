<?php


namespace App;


class Type
{
    public static function getList($skuType)
    {
        switch ($skuType) {

            case 'tshirts' :
            case 'toteBags':
            case 'hoodies':
            case 'kids':
                return [
                    ['key' => '0', 'name' => 'No Size']
                ];
            case 'mugs':
                return [
                    ['key' => 'S', 'name' => 'Standard 11oz'],
                    ['key' => 'X', 'name' => 'Mega 15oz']
                ];
            case 'cushionCovers':
                return [
                    ['key' => 'Q', 'name' => 'Sequin'],
                    ['key' => 'N', 'name' => 'Linen']
                ];
            default:
                return [];
        }
    }
}
