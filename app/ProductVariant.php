<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = "product_variants";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function master(){
        return $this->belongsTo('App\ProductMaster', 'product_master', 'id');
    }

    public function variantModel() {
        return $this->belongsTo('App\Variant', 'variant', 'id');
    }
}
