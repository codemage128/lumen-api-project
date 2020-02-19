<?php


namespace App;


class Color
{
    public static function getList($skyType){

        switch ($skyType){
            case 'tshirts':
                return [
                    ['key' => 'BK', 'name' => 'Black'],
                    ['key' => 'WH', 'name' => 'White'],
                    ['key' => 'RD', 'name' => 'Red'],
                    ['key' => 'GR', 'name' => 'Grey'],
                    ['key' => 'GN', 'name' => 'Green'],
                    ['key' => 'PR', 'name' => 'Purple'],
                    ['key' => 'YL', 'name' => 'Yellow'],
                    ['key' => 'LB', 'name' => 'Light Blue'],
                    ['key' => 'RB', 'name' => 'Royal Blue'],
                    ['key' => 'PK', 'name' => 'Pink'],
                    ['key' => 'NV', 'name' => 'Navy'],
                    ['key' => 'CH', 'name' => 'Charcoal']
                ];
            case 'mugs':
                return [
                    ['key' => 'BK', 'name' => 'Black'],
                    ['key' => 'WH', 'name' => 'White'],
                    ['key' => 'RD', 'name' => 'Red'],
                    ['key' => 'PK', 'name' => 'Pink'],
                    ['key' => 'GN', 'name' => 'Green'],
                    ['key' => 'BL', 'name' => 'Blue']
                ];
            case 'cushionCovers':
                return [
                    ['key' => 'BK', 'name' => 'Black'],
                    ['key' => 'SL', 'name' => 'Silver'],
                    ['key' => 'GD', 'name' => 'Gold'],
                    ['key' => 'PR', 'name' => 'Pearl'],
                    ['key' => 'RD', 'name' => 'Red'],
                    ['key' => 'BL', 'name' => 'Blue'],
                    ['key' => 'NA', 'name' => 'No Colour']
                ];
            case 'toteBags':
                return [
                    ['key' => 'BK', 'name' => 'Black'],
                    ['key' => 'WH', 'name' => 'White']
                ];
            case 'hoodies':
                return [
                    ['key' => 'BK', 'name' => 'Black'],
                    ['key' => 'WH', 'name' => 'White'],
                    ['key' => 'NV', 'name' => 'Navy'],
                    ['key' => 'GR', 'name' => 'Grey']
                ];
            case 'kids':
                return [
                    ['key' => 'BK', 'name' => 'Black'],
                    ['key' => 'WH', 'name' => 'White'],
                    ['key' => 'RD', 'name' => 'Red'],
                    ['key' => 'GR', 'name' => 'Grey'],
                    ['key' => 'GN', 'name' => 'Green'],
                    ['key' => 'PR', 'name' => 'Purple'],
                    ['key' => 'YL', 'name' => 'Yellow'],
                    ['key' => 'LB', 'name' => 'Light Blue'],
                    ['key' => 'PK', 'name' => 'Pink'],
                    ['key' => 'NV', 'name' => 'Navy']
                ];
            default:
                return [];
        }
    }
}
