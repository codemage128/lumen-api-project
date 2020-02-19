<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function generateItemTitle($master_type)
    {
        $genderList = [['key' => 'F', 'name' => 'Womens'], ['key' => 'M', 'name' => 'Mens']];
        $colorList = Color::getList($master_type);
        $sizeList = Size::getList($master_type);
        $gendername = "";
        $colorname = "";
        $sizename = "";
        foreach ($genderList as $gender) {
            if ($gender['key'] == $this->gender)
                $gendername = $gender['name'];
        }
        foreach ($colorList as $color) {
            if ($color['key'] == $this->color)
                $colorname = $color['name'];
        }
        foreach ($sizeList as $size) {
            if ($size['key'] == $this->size)
                $sizename = $size['name'];
        }

        if($master_type == 'tshirts') {
            $title = $gendername . " " . $colorname . " ". $sizename;
        } else {
            $title = $gendername . " " . $colorname;
        }
        return $title;
    }
}
