<?php

namespace App;


use App\Http\Controllers\ArtistController;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Fqsen;
use function MongoDB\BSON\toJSON;

class ProductMaster extends Model
{
    protected $table = "product_masters";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function productModel()
    {
        return $this->belongsTo('App\Product', 'product', 'id');
    }

    public function productVariants()
    {
        return $this->hasMany('App\ProductVariant', 'product_master', 'id');
    }

    public function createVariants()
    {
        $artist = $this->productModel->artistModel->code;
        $variants = Variant::where(['master_type' => $this->master_type])->get();

        foreach ($variants as $variant) {
            switch ($this->master_type) {
                case 'tshirts':
                    $type = "T";
                    break;
                case 'mugs':
                    $type = "M";
                    break;
                case 'toteBags':
                    $type = "B";
                    break;
                case 'cushionCovers':
                    $type = "C";
                    break;
                case 'hoodies':
                    $type = "H";
                    break;
                case 'kids':
                    $type = "K";
                    break;
                default:
                    break;
            }

            $colorList = Color::getList($this->master_type);
            foreach ($colorList as $color) {
                if ($color['key'] == $variant->color)
                    $colorname = $color['name'];
            }

            $sizeList = Size::getList($this->master_type);
            foreach ($sizeList as $size) {
                if ($size['key'] == $variant->size)
                    $sizename = $size['name'];
            }

            $sku_number_variant = $type . $variant->gender . $this->productModel->tshirt_print_mode . $this->productModel->product_no . $variant->color . $variant->size . "-" . $artist;
            $variant_title = $this->productModel->product_title . " " . Variant::where(['id' => $variant->id])->first()->generateItemTitle($this->master_type);
            if ($variant->gender == "") {
                $sku_number_variant = $type . "U". $this->productModel->tshirt_print_mode . $this->productModel->product_no . $variant->color . $variant->size . "-" . $artist;
            }
            if ($this->master_type == "mugs") {
                $sku_number_variant = $type . $variant->type . 'G' . $this->productModel->product_no . $variant->color . $variant->size . "-" . $artist;
            }
            if($this->master_type == "cushionCovers"){
                $sku_number_variant = $type . $variant->type . $this->productModel->tshirt_print_mode . $this->productModel->product_no . $variant->color . $variant->size . "-" . $artist;
            }


            if ($this->master_type == 'tshirts') {
                if ($sizename == 'Small') {
                    $variant_image = ucfirst($this->master_type) . "/" . $this->sku_no . "-" . $this->productModel->product_title . "/Images/" .
                        Utility::getProductVariantFileName($sku_number_variant, $this->productModel->product_title, $colorname);
                } else {
                    $variant_image = '';
                }
            } else {
                $variant_image = ucfirst($this->master_type) . "/" . $this->sku_no . "-" . $this->productModel->product_title . "/Images/" .
                    Utility::getProductVariantFileName($sku_number_variant, $this->productModel->product_title, $colorname);
            }



            ProductVariant::create([
                'product' => $this->productModel->id,
                'product_master' => $this->id,
                'variant' => $variant->id,
                'sku_no' => $sku_number_variant,
                'variant_title' => $variant_title,
                'image' => $variant_image
            ]);
        }
    }

    public function createExcelMaster()
    {
        $sizeDic = [];

        foreach (Size::getList($this->master_type) as $size) {
            $sizeDic[$size['key']] = $size['name'];
        }

        $colorDic = [];

        foreach (Color::getList($this->master_type) as $color) {
            $colorDic[$color['key']] = $color['name'];
        }

        $typeDic = [];

        foreach (Type::getList($this->master_type) as $type) {
            $typeDic[$type['key']] = $type['name'];
        }

        $genderDic = ['F' => "Womens", 'M' => "Mens"];
        $setting = Setting::first();
        $merchant_price = MerchantPrice::where('master', $this->master_type)->first();

        $Stock_available_level = 9999999;
        $Stock_level = 9999999;
        $Stock_minimum_level = '';
        $location = 'Default';
        $description = '';
        $sku_no = $this->sku_no;
        $brand_name = $setting->brand;
        $weight = $this->productModel->weight;
        $product_title = $this->productModel->product_title;
        $variantion_group_name = $product_title;
        $category = $this->productModel->categoryModel->name;
        $keywords = $this->productModel->keywordModel->tshirts;
        $merchant_price_au = $merchant_price->australia_price;
        $merchant_price_ca = $merchant_price->canada_price;
        $merchant_price_us = $merchant_price->usa_price;
        $merchant_price_uk = $merchant_price->uk_price;
        $merchant_price_europe = $merchant_price->europe_price;
        $master_title = $variantion_group_name . ' ' . $keywords;
        $website_title = '';
        $dropbox_root = $setting->dropbox_root;
        $primary_image_master = '[dropbox]' . '/' . $dropbox_root . '/' . $this->image_primary;
        $image2 = '[dropbox]/' . $this->image1;
        $image3 = '[dropbox]/' . $this->image2;
        $image4 = '[dropbox]/' . $this->image3;
        $image5 = '[dropbox]/' . $this->image4;
        $image6 = '[dropbox]/' . $this->image5;
        $image7 = '[dropbox]/' . $this->image6;

        $data = [
            'sku_no' => $sku_no, 'product_title' => $product_title, 'brand_name' => $brand_name, 'weight' => $weight, 'location' => $location,
            'Stock_available_level' => $Stock_available_level, 'variantion_group_name' => $variantion_group_name, 'merchant_price_ca' => $merchant_price_ca,
            'Stock_level' => $Stock_level, 'Stock_minimum_level' => $Stock_minimum_level, 'merchant_price_au' => $merchant_price_au,
            'merchant_price_us' => $merchant_price_us, 'merchant_price_uk' => $merchant_price_uk, 'merchant_price_europe' => $merchant_price_europe,
            'master_title' => $master_title, 'description' => $description, 'website_title' => $website_title, 'primary_image_master' => $primary_image_master,
            'image2' => $image2, 'image3' => $image3, 'image4' => $image4, 'image5' => $image5, 'image6' => $image6, 'image7' => $image7, 'colorDic' => $colorDic,
            'sizeDic' => $sizeDic, 'typeDic' => $typeDic, 'dropbox_root' => $dropbox_root, 'category' => $category, 'keywords' => $keywords, 'genderDic' => $genderDic
        ];

        switch ($this->master_type) {
            case 'tshirts':
                return $this->createExcelTshirtFile($data);
            case 'mugs':
                return $this->createExcelMugsFile($data);
            case 'stickers':
                return $this->createExcelStickersFile($data);
            case 'kids':
                return $this->createExcelKidsFile($data);
            case 'hoodies':
                return $this->createExcelHoodiesFile($data);
            case 'toteBags':
                return $this->createExcelToteBagsFile($data);
            case 'cushionCovers':
                return $this->createExcelcushionCoversFile($data);
        }
    }

    public function createExcelTshirtFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $location = $data['location'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $primary_image_master = $data['primary_image_master'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $colorDic = $data['colorDic'];
        $sizeDic = $data['sizeDic'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];
        $genderDic = $data['genderDic'];

        $keywords = $this->productModel->keywordModel->mugs;
        $tshirt_price = $this->productModel->tshirt_price;
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' - ' . $brand_name;
        $seo_description = 'Discover thousands of Artist Designed ' . $category . " T-shirts, look no further! Our tees are soft and high quality, they are all unique and great for Men Women Kids and Babies. We've got Hoodies, Mugs, Singlets, and Stickers and more!";

        $fileName = public_path('excels/Tshirts---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Is Variantion Group', 'Variation SKU', 'Variantion Group Name',
            'Item Title', 'Short Description', 'Retail Price', 'Category',
            'Brand', 'Weight', 'Height', 'Dim Width',
            'Stock available level at location', 'Stock level at location', 'Stock minimum level at location', 'Colour',
            'Manufacturer Part Number', 'Location', 'Size', 'Amazon AU Price',
            'eBay AU Price', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'Amazon FR Price', 'Amazon IT Price', 'Amazon ES Price', 'eBay IE Price',
            'Amazon AU Title', 'eBay AU Title', 'eBay US Title', 'eBay CA Title',
            'eBay UK Title', 'eBay IE Title', 'Amazon US Title', 'Amazon UK Title',
            'Amazon DE Title', 'Amazon CA Title', 'Amazon FR Title', 'Amazon IT Title',
            'Amazon ES Title', 'Default Description', 'Amazon AU Description', 'Amazon US Description',
            'Amazon UK Description', 'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description',
            'Amazon IT Description', 'Amazon ES Description', 'SEO Title Tags', 'SEO Description Tags',
            'Website Title', 'PrimaryImage', 'Image2', 'Image3',
            'Image4', 'Image5', 'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, 'Yes', $sku_no, $variantion_group_name,
            $variantion_group_name, '', $tshirt_price, $category,
            $brand_name, $weight, '', '',
            $Stock_available_level, $Stock_level, $Stock_minimum_level, '',
            $sku_no, $location, '', $tshirt_price + $merchant_price_au,
            $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
            $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
            $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $description, $seo_title_master, $seo_description,
            $website_title, $primary_image_master, $image2, $image3,
            $image4, $image5, $image6, $image7
        ]);

        foreach ($this->productVariants as $productVariant) {                                           //Variants
            $variant = $productVariant->variantModel;
            $variant_name = $genderDic[$variant->gender] . ' ' . $colorDic[$variant->color];
            $variant_size = $sizeDic[$variant->size];
            $item_title = $variantion_group_name . ' ' . $variant_size . ' ' . $variant_name;
            $variant_image = $productVariant->image;
            $title = $variantion_group_name . ' ' . $keywords . ' ' . $variant_name;
            $seo_title = $variantion_group_name . ' | ' . $category . ' Tshirts ' . $genderDic[$variant->gender] . ' - ' . $brand_name;

            if($productVariant->image != ''){
                $primary_image = '[dropbox]' . '/' . $dropbox_root . '/' . $variant_image;
            }
            elseif($productVariant->image == '') {
                $primary_image = '';
            }

            fputcsv($file, [
                $productVariant->sku_no, 'No', $sku_no, $variantion_group_name,
                $item_title, '', $tshirt_price, $category,
                $brand_name, $weight, '', '',
                $Stock_available_level, $Stock_level, $Stock_minimum_level, $variant_name,
                $productVariant->sku_no, $location, $variant_size, $tshirt_price + $merchant_price_au,
                $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
                $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
                $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $description, $description, $description,
                $description, $description, $description, $description,
                $description, $description, $seo_title, $seo_description,
                $website_title, $primary_image, '', '',
                '', '', '', ''
            ]);
        }
        fclose($file);

        return $fileName;
    }

    public function createExcelMugsFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $location = $data['location'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $primary_image_master = $data['primary_image_master'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $colorDic = $data['colorDic'];
        $typeDic = $data['typeDic'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];

        $keywords = $this->productModel->keywordModel->mugs;
        $mugs_price = $this->productModel->mug_price;
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' - ' . $brand_name;
        $seo_description = 'This is the perfect ' . $category . '  mug that you are looking for! Discover a huge range of ' . $category . '  mugs that makes a unique gift for him or her. Ideal for Birthdays, Farewells, Anniversaries, Weddings, Pranks, Special Occasions and more!';

        $fileName = public_path('excels/Mugs---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Is Variantion Group', 'Variation SKU', 'Variantion Group Name',
            'Item Title', 'Short Description', 'Retail Price', 'Category',
            'Brand', 'Weight', 'Height', 'Dim Width',
            'Stock available level at location', 'Stock level at location', 'Stock minimum level at location', 'Colour',
            'Manufacturer Part Number', 'Location', 'Size', 'Amazon AU Price',
            'eBay AU Price', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'Amazon FR Price', 'Amazon IT Price', 'Amazon ES Price', 'eBay IE Price',
            'Amazon AU Title', 'eBay AU Title', 'eBay US Title', 'eBay CA Title',
            'eBay UK Title', 'eBay IE Title', 'Amazon US Title', 'Amazon UK Title',
            'Amazon DE Title', 'Amazon CA Title', 'Amazon FR Title', 'Amazon IT Title',
            'Amazon ES Title', 'Default Description', 'Amazon AU Description', 'Amazon US Description',
            'Amazon UK Description', 'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description',
            'Amazon IT Description', 'Amazon ES Description', 'SEO Title Tags', 'SEO Description Tags',
            'Website Title', 'PrimaryImage', 'Image2', 'Image3',
            'Image4', 'Image5', 'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, 'Yes', $sku_no, $variantion_group_name,
            $variantion_group_name, '', $mugs_price, $category,
            $brand_name, $weight, '', '',
            $Stock_available_level, $Stock_level, $Stock_minimum_level, '',
            $sku_no, $location, '', $mugs_price + $merchant_price_au,
            $mugs_price + $merchant_price_au, $mugs_price + $merchant_price_ca, $mugs_price + $merchant_price_ca, $mugs_price + $merchant_price_us,
            $mugs_price + $merchant_price_us, $mugs_price + $merchant_price_uk, $mugs_price + $merchant_price_uk, $mugs_price + $merchant_price_europe,
            $mugs_price + $merchant_price_europe, $mugs_price + $merchant_price_europe, $mugs_price + $merchant_price_europe, $mugs_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $description, $seo_title_master, $seo_description,
            $website_title, $primary_image_master, $image2, $image3,
            $image4, $image5, $image6, $image7
        ]);

        foreach ($this->productVariants as $productVariant) {                                           //Variants
            $variant = $productVariant->variantModel;
            $variant_name = $typeDic[$variant->type] . ' ' . $colorDic[$variant->color];
            $item_title = $variantion_group_name . ' ' . $variant_name;
            $variant_type = $typeDic[$variant->type];
            $variant_image = $productVariant->image;
            $title = $variantion_group_name . ' ' . $keywords . ' ' . $variant_name;
            $seo_title = $variantion_group_name . ' | ' . $category . ' Mugs ' . $variant_type . ' - ' . $brand_name;
            $primary_image = '[dropbox]' . '/' . $dropbox_root . '/' . $variant_image;

            fputcsv($file, [
                $productVariant->sku_no, 'No', $sku_no, $variantion_group_name,
                $item_title, '', $mugs_price, $category,
                $brand_name, $weight, '', '',
                $Stock_available_level, $Stock_level, $Stock_minimum_level, $variant_name,
                $productVariant->sku_no, $location, $variant_type, $mugs_price + $merchant_price_au,
                $mugs_price + $merchant_price_au, $mugs_price + $merchant_price_ca, $mugs_price + $merchant_price_ca, $mugs_price + $merchant_price_us,
                $mugs_price + $merchant_price_us, $mugs_price + $merchant_price_uk, $mugs_price + $merchant_price_uk, $mugs_price + $merchant_price_europe,
                $mugs_price + $merchant_price_europe, $mugs_price + $merchant_price_europe, $mugs_price + $merchant_price_europe, $mugs_price + $merchant_price_europe,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $description, $description, $description,
                $description, $description, $description, $description,
                $description, $description, $seo_title, $seo_description,
                $website_title, $primary_image, '', '',
                '', '', '', ''
            ]);
        }
        fclose($file);

        return $fileName;
    }

    public function createExcelStickersFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $product_title = $data['product_title'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];

        $keywords = $this->productModel->keywordModel->tshirts;
        $sticker_price = $this->productModel->stickers_price;
        $sticker_width = $this->productModel->stickers_width;
        $sticker_height = $this->productModel->stickers_height;
        $short_description = "<strong>Dimensions:" . $sticker_width . " mm x " . $sticker_height . " mm </strong><br>Die-cut on quality vinyl  <br>Printed with the latest technology  <br>Waterproof and UV proof  <br>Ideal for cars, laptops, waterbottles, skateboards and any clean flat surface";
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' Stickers - ' . $brand_name;
        $seo_description = 'Shop from a large collection of 10000+ unique designs including ' . $category . '  stickers that you are looking for! All our stickers are waterproof and UV proof, the are great for cars, vans, laptops, waterbottles, skateboards, notebooks and any clean non-porous surface.';
        $primary_image_master = '[dropbox]' . '/' . $dropbox_root . '/' . $this->image_primary;
        $stock_location = 'Default';
        $inche_width = number_format($sticker_width * 1 / 25.4, 2);
        $inche_height = number_format($sticker_height * 1 / 25.4, 2);
        $product_name = 'Dimensions: ' . $sticker_width . 'mm x ' . $sticker_height . 'mm (' . $inche_width . ' x ' . $inche_height . ' inches) - Great Size!';
        $shopify_id = '';

        $fileName = public_path('excels/Stickers---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Item Title', 'Short Description', 'Retail Price',
            'Category', 'Brand', 'Weight', 'Dim Width',
            'Height', 'Is Variation Parent', 'Stock available level at location', 'Stock level at location',
            'Stock minimum level at location', 'Colour', 'Manufacturer Part Number', 'Option',
            'Size', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay Motors US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'eBay FR Price', 'Amazon IT Price', 'Amazon ES Price', 'Amazon IE Price',
            'Amazon US Title', 'Amazon UK Title', 'Amazon DE Title', 'Amazon CA Title',
            'Amazon FR Title', 'Amazon IT Title', 'Amazon ES Title', 'eBay AU Title',
            'eBay Motors US Title', 'eBay CA Title', 'eBay UK Title', 'eBay IE Title',
            'Shopify Title', 'Default Description', 'Amazon US Description', 'Amazon UK Description',
            'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description', 'Amazon IT Description',
            'Amazon ES Description', 'Stock Location', 'Amazon AU Price', 'Amazon AU Description',
            'Amazon AU Title', 'eBay AU Price', 'Product WxH', 'Shopify Category ID', 'Shopify Tags', 'SEO Title Tags', 'SEO Description Tags', 'Website Title',
            'PrimaryImage', 'Image2', 'Image3', 'Image4', 'Image5',
            'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, $product_title, $short_description, $sticker_price,
            $category, $brand_name, $weight, $sticker_width,
            $sticker_height, 'FALSE', $Stock_available_level, $Stock_level,
            $Stock_minimum_level, '', $sku_no, '',
            '', $sticker_price + $merchant_price_ca, $sticker_price + $merchant_price_ca, $sticker_price + $merchant_price_us,
            $sticker_price + $merchant_price_us, $sticker_price + $merchant_price_uk, $sticker_price + $merchant_price_uk, $sticker_price + $merchant_price_europe,
            $sticker_price + $merchant_price_europe, $sticker_price + $merchant_price_europe, $sticker_price + $merchant_price_europe, $sticker_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $product_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $product_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $stock_location, $sticker_price + $merchant_price_au, $description,
            $master_title, $sticker_price + $merchant_price_au, $product_name, $shopify_id, $category, $seo_title_master,
            $seo_description, $website_title, $primary_image_master, $image2,
            $image3, $image4, $image5, $image6, $image7
        ]);

        fclose($file);

        return $fileName;
    }

    public function createExcelKidsFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $location = $data['location'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $primary_image_master = $data['primary_image_master'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $colorDic = $data['colorDic'];
        $sizeDic = $data['sizeDic'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];

        $keywords = $this->productModel->keywordModel->mugs;
        $tshirt_price = $this->productModel->tshirt_price;
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' Youth Tshirts - ' . $brand_name;
        $seo_description = 'Shop from a large range of unique ' . $category . " T-Shirts for Kids and Teens. Discover our high quality printed, cotton teeshirts that range from XS to XL to suit any age from Kids to Teens and even Adults!";

        $fileName = public_path('excels/Kids---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Is Variantion Group', 'Variation SKU', 'Variantion Group Name',
            'Item Title', 'Short Description', 'Retail Price', 'Category',
            'Brand', 'Weight', 'Height', 'Dim Width',
            'Stock available level at location', 'Stock level at location', 'Stock minimum level at location', 'Colour',
            'Manufacturer Part Number', 'Location', 'Size', 'Amazon AU Price',
            'eBay AU Price', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'Amazon FR Price', 'Amazon IT Price', 'Amazon ES Price', 'eBay IE Price',
            'Amazon AU Title', 'eBay AU Title', 'eBay US Title', 'eBay CA Title',
            'eBay UK Title', 'eBay IE Title', 'Amazon US Title', 'Amazon UK Title',
            'Amazon DE Title', 'Amazon CA Title', 'Amazon FR Title', 'Amazon IT Title',
            'Amazon ES Title', 'Default Description', 'Amazon AU Description', 'Amazon US Description',
            'Amazon UK Description', 'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description',
            'Amazon IT Description', 'Amazon ES Description', 'SEO Title Tags', 'SEO Description Tags',
            'Website Title', 'PrimaryImage', 'Image2', 'Image3',
            'Image4', 'Image5', 'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, 'Yes', $sku_no, $variantion_group_name,
            $variantion_group_name, '', $tshirt_price, $category,
            $brand_name, $weight, '', '',
            $Stock_available_level, $Stock_level, $Stock_minimum_level, '',
            $sku_no, $location, '', $tshirt_price + $merchant_price_au,
            $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
            $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
            $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $description, $seo_title_master, $seo_description,
            $website_title, $primary_image_master, $image2, $image3,
            $image4, $image5, $image6, $image7
        ]);

        foreach ($this->productVariants as $productVariant) {                                           //Variants
            $variant = $productVariant->variantModel;
            $variant_name = $sizeDic[$variant->size] . ' ' . $colorDic[$variant->color];
            $variant_colour = 'Kids ' . $colorDic[$variant->color];
            $item_title = $variantion_group_name . ' ' . $variant_name;
            $variant_size = $sizeDic[$variant->size];
            $variant_image = $productVariant->image;
            $title = $variantion_group_name . ' ' . $keywords . ' ' . $variant_colour;
            $seo_title = $variantion_group_name . ' | ' . $category . ' Youth Tshirts ' . $variant_size . ' - ' . $brand_name;
            $primary_image = '[dropbox]' . '/' . $dropbox_root . '/' . $variant_image;

            fputcsv($file, [
                $productVariant->sku_no, 'No', $sku_no, $variantion_group_name,
                $item_title, '', $tshirt_price, $category,
                $brand_name, $weight, '', '',
                $Stock_available_level, $Stock_level, $Stock_minimum_level, $variant_colour,
                $productVariant->sku_no, $location, $variant_size, $tshirt_price + $merchant_price_au,
                $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
                $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
                $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $description, $description, $description,
                $description, $description, $description, $description,
                $description, $description, $seo_title, $seo_description,
                $website_title, $primary_image, '', '',
                '', '', '', ''
            ]);
        }
        fclose($file);

        return $fileName;
    }

    public function createExcelHoodiesFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $location = $data['location'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $primary_image_master = $data['primary_image_master'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $colorDic = $data['colorDic'];
        $sizeDic = $data['sizeDic'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];

        $keywords = $this->productModel->keywordModel->mugs;
        $tshirt_price = $this->productModel->tshirt_price;
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' Hoodies - ' . $brand_name;
        $seo_description = 'Shop our large selection of ' . $category . " Hoodies! Our high quality, unique hoodies comes in many colours and sizes from small to 5XL! They are soft and comfortable, ideal gifts for him and her, Birthdays, Farewells, Anniversaries, Weddings, Pranks, Special Occasions and more!";

        $fileName = public_path('excels/Hoodies---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Is Variantion Group', 'Variation SKU', 'Variantion Group Name',
            'Item Title', 'Short Description', 'Retail Price', 'Category',
            'Brand', 'Weight', 'Height', 'Dim Width',
            'Stock available level at location', 'Stock level at location', 'Stock minimum level at location', 'Colour',
            'Manufacturer Part Number', 'Location', 'Size', 'Amazon AU Price',
            'eBay AU Price', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'Amazon FR Price', 'Amazon IT Price', 'Amazon ES Price', 'eBay IE Price',
            'Amazon AU Title', 'eBay AU Title', 'eBay US Title', 'eBay CA Title',
            'eBay UK Title', 'eBay IE Title', 'Amazon US Title', 'Amazon UK Title',
            'Amazon DE Title', 'Amazon CA Title', 'Amazon FR Title', 'Amazon IT Title',
            'Amazon ES Title', 'Default Description', 'Amazon AU Description', 'Amazon US Description',
            'Amazon UK Description', 'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description',
            'Amazon IT Description', 'Amazon ES Description', 'SEO Title Tags', 'SEO Description Tags',
            'Website Title', 'PrimaryImage', 'Image2', 'Image3',
            'Image4', 'Image5', 'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, 'Yes', $sku_no, $variantion_group_name,
            $variantion_group_name, '', $tshirt_price, $category,
            $brand_name, $weight, '', '',
            $Stock_available_level, $Stock_level, $Stock_minimum_level, '',
            $sku_no, $location, '', $tshirt_price + $merchant_price_au,
            $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
            $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
            $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $description, $seo_title_master, $seo_description,
            $website_title, $primary_image_master, $image2, $image3,
            $image4, $image5, $image6, $image7
        ]);

        foreach ($this->productVariants as $productVariant) {                                           //Variants
            $variant = $productVariant->variantModel;
            $variant_name = $sizeDic[$variant->size] . ' ' . $colorDic[$variant->color];
            $variant_colour = $colorDic[$variant->color];
            $item_title = $variantion_group_name . ' ' . $variant_name;
            $variant_size = $sizeDic[$variant->size];
            $variant_image = $productVariant->image;
            $title = $variantion_group_name . ' ' . $keywords . ' ' . $variant_colour;
            $seo_title = $variantion_group_name . ' | ' . $category . ' Hoodies ' . $variant_size . ' - ' . $brand_name;
            $primary_image = '[dropbox]' . '/' . $dropbox_root . '/' . $variant_image;

            fputcsv($file, [
                $productVariant->sku_no, 'No', $sku_no, $variantion_group_name,
                $item_title, '', $tshirt_price, $category,
                $brand_name, $weight, '', '',
                $Stock_available_level, $Stock_level, $Stock_minimum_level, $variant_colour,
                $productVariant->sku_no, $location, $variant_size, $tshirt_price + $merchant_price_au,
                $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
                $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
                $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $description, $description, $description,
                $description, $description, $description, $description,
                $description, $description, $seo_title, $seo_description,
                $website_title, $primary_image, '', '',
                '', '', '', ''
            ]);
        }
        fclose($file);

        return $fileName;
    }

    public function createExcelToteBagsFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $location = $data['location'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $primary_image_master = $data['primary_image_master'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $colorDic = $data['colorDic'];
        $sizeDic = $data['sizeDic'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];

        $keywords = $this->productModel->keywordModel->mugs;
        $tshirt_price = $this->productModel->tshirt_price;
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' Tote Bags - ' . $brand_name;
        $seo_description = 'Shop our large range of artist designed ' . $category . " Tote Bags today! Our unique, thick canvas Totes Bags are ideal for everyday use, making them great for Gifts, Birthdays, Farewells, Anniversaries, Weddings, Special Occasions and more!";

        $fileName = public_path('excels/ToteBags---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Is Variantion Group', 'Variation SKU', 'Variantion Group Name',
            'Item Title', 'Short Description', 'Retail Price', 'Category',
            'Brand', 'Weight', 'Height', 'Dim Width',
            'Stock available level at location', 'Stock level at location', 'Stock minimum level at location', 'Colour',
            'Manufacturer Part Number', 'Location', 'Size', 'Amazon AU Price',
            'eBay AU Price', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'Amazon FR Price', 'Amazon IT Price', 'Amazon ES Price', 'eBay IE Price',
            'Amazon AU Title', 'eBay AU Title', 'eBay US Title', 'eBay CA Title',
            'eBay UK Title', 'eBay IE Title', 'Amazon US Title', 'Amazon UK Title',
            'Amazon DE Title', 'Amazon CA Title', 'Amazon FR Title', 'Amazon IT Title',
            'Amazon ES Title', 'Default Description', 'Amazon AU Description', 'Amazon US Description',
            'Amazon UK Description', 'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description',
            'Amazon IT Description', 'Amazon ES Description', 'SEO Title Tags', 'SEO Description Tags',
            'Website Title', 'PrimaryImage', 'Image2', 'Image3',
            'Image4', 'Image5', 'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, 'Yes', $sku_no, $variantion_group_name,
            $variantion_group_name, '', $tshirt_price, $category,
            $brand_name, $weight, '', '',
            $Stock_available_level, $Stock_level, $Stock_minimum_level, '',
            $sku_no, $location, '', $tshirt_price + $merchant_price_au,
            $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
            $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
            $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $description, $seo_title_master, $seo_description,
            $website_title, $primary_image_master, $image2, $image3,
            $image4, $image5, $image6, $image7
        ]);

        foreach ($this->productVariants as $productVariant) {                                           //Variants
            $variant = $productVariant->variantModel;
            $variant_name = $sizeDic[$variant->size] . ' ' . $colorDic[$variant->color];
            $variant_colour = $colorDic[$variant->color];
            $item_title = $variantion_group_name . ' ' . $variant_name;
            $variant_size = $sizeDic[$variant->size];
            $variant_image = $productVariant->image;
            $title = $variantion_group_name . ' ' . $keywords . ' ' . $variant_colour;
            $seo_title = $variantion_group_name . ' | ' . $category . ' Tote Bags ' . $variant_size . ' - ' . $brand_name;
            $primary_image = '[dropbox]' . '/' . $dropbox_root . '/' . $variant_image;

            fputcsv($file, [
                $productVariant->sku_no, 'No', $sku_no, $variantion_group_name,
                $item_title, '', $tshirt_price, $category,
                $brand_name, $weight, '', '',
                $Stock_available_level, $Stock_level, $Stock_minimum_level, $variant_colour,
                $productVariant->sku_no, $location, $variant_size, $tshirt_price + $merchant_price_au,
                $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
                $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
                $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $description, $description, $description,
                $description, $description, $description, $description,
                $description, $description, $seo_title, $seo_description,
                $website_title, $primary_image, '', '',
                '', '', '', ''
            ]);
        }
        fclose($file);

        return $fileName;
    }

    public function createExcelcushionCoversFile($data)
    {
        $sku_no = $data['sku_no'];
        $brand_name = $data['brand_name'];
        $weight = $data['weight'];
        $Stock_available_level = $data['Stock_available_level'];
        $variantion_group_name = $data['variantion_group_name'];
        $Stock_level = $data['Stock_level'];
        $Stock_minimum_level = $data['Stock_minimum_level'];
        $location = $data['location'];
        $merchant_price_au = $data['merchant_price_au'];
        $merchant_price_ca = $data['merchant_price_ca'];
        $merchant_price_us = $data['merchant_price_us'];
        $merchant_price_uk = $data['merchant_price_uk'];
        $merchant_price_europe = $data['merchant_price_europe'];
        $master_title = $data['master_title'];
        $description = $data['description'];
        $website_title = $data['website_title'];
        $primary_image_master = $data['primary_image_master'];
        $image2 = $data['image2'];
        $image3 = $data['image3'];
        $image4 = $data['image4'];
        $image5 = $data['image5'];
        $image6 = $data['image6'];
        $image7 = $data['image7'];
        $colorDic = $data['colorDic'];
        $sizeDic = $data['sizeDic'];
        $typeDic = $data['typeDic'];
        $dropbox_root = $data['dropbox_root'];
        $category = $data['category'];

        $keywords = $this->productModel->keywordModel->mugs;
        $tshirt_price = $this->productModel->tshirt_price;
        $seo_title_master = $variantion_group_name . ' | ' . $category . ' Cushion Covers - ' . $brand_name;
        $seo_description = 'Shop our large range of artist designed ' . $category . " Cushion Covers from our shop today! Our Cushion Covers are ideal for everyday use, making them great for Gifts, Birthdays, Farewells, Anniversaries, Weddings, Special Occasions and more!";

        $fileName = public_path('excels/Cushion-Covers---' . $this->sku_no . '.csv');
        $file = fopen($fileName, 'w');

        $columns = array(
            'SKU', 'Is Variantion Group', 'Variation SKU', 'Variantion Group Name',
            'Item Title', 'Short Description', 'Retail Price', 'Category',
            'Brand', 'Weight', 'Height', 'Dim Width',
            'Stock available level at location', 'Stock level at location', 'Stock minimum level at location', 'Colour',
            'Manufacturer Part Number', 'Location', 'Type', 'Amazon AU Price',
            'eBay AU Price', 'Amazon CA Price', 'eBay CA Price', 'Amazon US Price',
            'eBay US Price', 'Amazon UK Price', 'eBay UK Price', 'Amazon DE Price',
            'Amazon FR Price', 'Amazon IT Price', 'Amazon ES Price', 'eBay IE Price',
            'Amazon AU Title', 'eBay AU Title', 'eBay US Title', 'eBay CA Title',
            'eBay UK Title', 'eBay IE Title', 'Amazon US Title', 'Amazon UK Title',
            'Amazon DE Title', 'Amazon CA Title', 'Amazon FR Title', 'Amazon IT Title',
            'Amazon ES Title', 'Default Description', 'Amazon AU Description', 'Amazon US Description',
            'Amazon UK Description', 'Amazon DE Description', 'Amazon CA Description', 'Amazon FR Description',
            'Amazon IT Description', 'Amazon ES Description', 'SEO Title Tags', 'SEO Description Tags',
            'Website Title', 'PrimaryImage', 'Image2', 'Image3',
            'Image4', 'Image5', 'Image6', 'Image7'
        );
        //Header
        fputcsv($file, $columns);
        //Master Case
        fputcsv($file, [
            $sku_no, 'Yes', $sku_no, $variantion_group_name,
            $variantion_group_name, '', $tshirt_price, $category,
            $brand_name, $weight, '', '',
            $Stock_available_level, $Stock_level, $Stock_minimum_level, '',
            $sku_no, $location, '', $tshirt_price + $merchant_price_au,
            $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
            $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
            $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $master_title, $master_title, $master_title,
            $master_title, $description, $description, $description,
            $description, $description, $description, $description,
            $description, $description, $seo_title_master, $seo_description,
            $website_title, $primary_image_master, $image2, $image3,
            $image4, $image5, $image6, $image7
        ]);

        foreach ($this->productVariants as $productVariant) {                                           //Variants
            $variant = $productVariant->variantModel;
            $variant_name = $typeDic[$variant->type] . ' ' . $colorDic[$variant->color];
            $variant_colour = $colorDic[$variant->color];
            $item_title = $variantion_group_name . ' ' . $variant_name;
            $variant_size = $sizeDic[$variant->size];
            $variant_image = $productVariant->image;
            $title = $variantion_group_name . ' ' . $keywords . ' ' . $variant_name;
            $seo_title = $variantion_group_name . ' | ' . $category . ' Cushion Covers ' . $typeDic[$variant->type] . ' - ' . $brand_name;
            $seo_description = 'Shop our large range of artist designed ' . $category . ' ' . $typeDic[$variant->type] . " Cushion Covers from our shop today! Our " . $typeDic[$variant->type] . "Cushion Covers are ideal for everyday use, making them great for Gifts, Birthdays, Farewells, Anniversaries, Weddings, Special Occasions and more!";
            $primary_image = '[dropbox]' . '/' . $dropbox_root . '/' . $variant_image;

            fputcsv($file, [
                $productVariant->sku_no, 'No', $sku_no, $variantion_group_name,
                $item_title, '', $tshirt_price, $category,
                $brand_name, $weight, '', '',
                $Stock_available_level, $Stock_level, $Stock_minimum_level, $variant_name,
                $productVariant->sku_no, $location, $variant_size, $tshirt_price + $merchant_price_au,
                $tshirt_price + $merchant_price_au, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_ca, $tshirt_price + $merchant_price_us,
                $tshirt_price + $merchant_price_us, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_uk, $tshirt_price + $merchant_price_europe,
                $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe, $tshirt_price + $merchant_price_europe,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $title, $title, $title,
                $title, $description, $description, $description,
                $description, $description, $description, $description,
                $description, $description, $seo_title, $seo_description,
                $website_title, $primary_image, '', '',
                '', '', '', ''
            ]);
        }
        fclose($file);

        return $fileName;
    }

}
