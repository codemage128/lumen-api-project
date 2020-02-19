<?php


namespace App;

class Size
{
    public static function getList($skuType)
    {
        switch ($skuType) {
            case 'tshirts' :
                return [
                    ['key' => 'S', 'name' => 'Small'],
                    ['key' => 'M', 'name' => 'Medium'],
                    ['key' => 'L', 'name' => 'L'],
                    ['key' => '1', 'name' => 'XL'],
                    ['key' => '2', 'name' => '2XL'],
                    ['key' => '3', 'name' => '3XL']
                ];
            case 'mugs':
                return [
                    ['key' => '0', 'name' => 'No size'],
                ];
            case "cushionCovers":
                return [
                    ['key' => '0', 'name' => 'No size'],
                ];
            case 'toteBags':
                return [
                    ['key' => '0', 'name' => 'No size'],
                ];
            case 'hoodies':
                return [
                    ['key' => 'S', 'name' => 'Small'],
                    ['key' => 'M', 'name' => 'Medium'],
                    ['key' => 'L', 'name' => 'L'],
                    ['key' => '1', 'name' => 'XL'],
                    ['key' => '2', 'name' => '2XL'],
                    ['key' => '3', 'name' => '3XL'],
                    ['key' => '4', 'name' => '4XL'],
                    ['key' => '5', 'name' => '5XL']
                ];
            case 'kids':
                return [
                    ['key' => 'E', 'name' => 'Extra small'],
                    ['key' => 'S', 'name' => 'Small'],
                    ['key' => 'M', 'name' => 'Medium'],
                    ['key' => 'L', 'name' => 'L'],
                    ['key' => '1', 'name' => 'XL']
                ];
            default:
                return [];
        }
    }
}
