<?php


namespace App\Http\Controllers;


use App\Artist;
use App\Category;
use App\Color;
use App\Information;
use App\Keyword;
use App\MerchantPrice;
use App\Size;
use App\Type;
use App\Product;
use App\ProductMaster;
use App\ProductVariant;
use App\Setting;
use App\ProductImageSet;
use App\Variant;
use App\VariantPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductController extends ApiController
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function allProductInfo()
    {
        $allProductList = array();

        $artistList = $this->artistList();
        $stickerTypeList = $this->stickertypeList();
        $printModeList = $this->printmodeList();
        $productImageList = $this->productImageList();
        $priceTshirtList = $this->priceList("tshirts");
        $pricestickers = $this->priceList("stickers");
        $pricemugs = $this->priceList("mugs");
        $pricetoteBags = $this->priceList("toteBags");
        $pricecushionCovers = $this->priceList("cushionCovers");
        $pricehoodies = $this->priceList("hoodies");
        $pricekids = $this->priceList("kids");

        array_push($allProductList, $artistList);
        array_push($allProductList, $stickerTypeList);
        array_push($allProductList, $printModeList);
        array_push($allProductList, $priceTshirtList);
        array_push($allProductList, $pricestickers);
        array_push($allProductList, $pricemugs);
        array_push($allProductList, $pricetoteBags);
        array_push($allProductList, $pricecushionCovers);
        array_push($allProductList, $pricehoodies);
        array_push($allProductList, $pricekids);
        array_push($allProductList, $productImageList);
        return $this->success($allProductList);
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $result = array();
        $productMasters = ProductMaster::where('product', $id)->get();

        foreach ($productMasters as $productMaster) {
            $itemArray = [];
            $master_type = $productMaster->master_type;
            $master_id = DB::selectOne(DB::raw("select id from product_masters WHERE master_type = '$master_type' and  product = '$id'"))->id;
            switch ($master_type) {
                case "stickers":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.stickers as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.stickers_price as price, products.stickers_width,
                    products.stickers_height ,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $itemArray = collect($query_master);
                    break;
                case "tshirts":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.tshirts as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.tshirt_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $query_variants = DB::select(DB::raw("select product_variants.sku_no as sku,
                    product_variants.variant_title as title, keywords.tshirts as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.tshirt_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_variants
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where product_master = '$master_id';"));
                    $itemArray = collect($query_master)->union(collect($query_variants));
                    break;
                case "mugs":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.mugs as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.mug_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $query_variants = DB::select(DB::raw("select product_variants.sku_no as sku,
                    product_variants.variant_title as title, keywords.mugs as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.mug_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_variants
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where product_master = '$master_id';"));
                    $itemArray = collect($query_master)->union(collect($query_variants));
                    break;
                case "toteBags":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.tote_bags as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.tote_bag_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $query_variants = DB::select(DB::raw("select product_variants.sku_no as sku,
                    product_variants.variant_title as title, keywords.tote_bags as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.tote_bag_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_variants
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where product_master = '$master_id';"));
                    $itemArray = collect($query_master)->union(collect($query_variants));
                    break;
                case "cushionCovers":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.cushion_covers as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.cushion_cover_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $query_variants = DB::select(DB::raw("select product_variants.sku_no as sku,
                    product_variants.variant_title as title, keywords.cushion_covers as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.cushion_cover_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_variants
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where product_master = '$master_id';"));
                    $itemArray = collect($query_master)->union(collect($query_variants));
                    break;
                case "hoodies":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.hoodies as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.hoodie_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $query_variants = DB::select(DB::raw("select product_variants.sku_no as sku,
                    product_variants.variant_title as title, keywords.hoodies as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.hoodie_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_variants
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where product_master = '$master_id';"));
                    $itemArray = collect($query_master)->union(collect($query_variants));
                    break;
                case "kids":
                    $query_master = DB::select(DB::raw("select product_masters.sku_no as sku,
                    products.product_title as title, keywords.kids as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.kid_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_masters
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where master_type ='$master_type'"));
                    $query_variants = DB::select(DB::raw("select product_variants.sku_no as sku,
                    product_variants.variant_title as title, keywords.kids as keyword,
                    categories.`name` as category, artists.`code` as artist,
                    products.kid_price as price, products.weight as weight,
                    case when products.tshirt_print_mode = 'P' then 'Print' else 'Vinly' end as print
                    from product_variants
                    join products on products.id ='$id'
                    join keywords
                    join categories on categories.id = products.category
                    join artists on artists.id = products.artist
                    where product_master = '$master_id';"));
                    $itemArray = collect($query_master)->union(collect($query_variants));
                    break;
            }
            array_push($result, $itemArray);
        }

        return $this->success($result);
    }

    public function list(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $column = $request->column;
        $order = $request->order;
        $query = DB::select(DB::raw("select products.id, products.product_title as title,
            stickers.image_primary as image_stickers, stickers.is_upload as isupload_stickers, stickers.sku_no as name_stickers,
            tshirts.image_primary as image_tshirts, tshirts.is_upload as isupload_tshirts, tshirts.sku_no as name_tshirts,
            mugs.image_primary as image_mugs, mugs.is_upload as isupload_mugs,  mugs.sku_no as name_mugs,
            toteBags.image_primary as image_bags, toteBags.is_upload as isupload_bags, toteBags.sku_no as name_bags,
            cushionCovers.image_primary as image_covers, cushionCovers.is_upload as isupload_covers,  cushionCovers.sku_no as name_covers,
            kids.image_primary as image_kids, kids.is_upload as isupload_kids,   kids.sku_no as name_kids,
            hoodies.image_primary as image_hoodies, hoodies.is_upload as isupload_hoodies, hoodies.sku_no as name_hoodies
            from products
            join product_masters as stickers on products.id = stickers.product and stickers.master_type = 'stickers'
            join product_masters as tshirts on products.id = tshirts.product and tshirts.master_type = 'tshirts'
            join product_masters as mugs on products.id = mugs.product and mugs.master_type = 'mugs'
            join product_masters as toteBags on products.id = toteBags.product and toteBags.master_type = 'toteBags'
            join product_masters as cushionCovers on products.id = cushionCovers.product and cushionCovers.master_type = 'cushionCovers'
            join product_masters as kids on products.id = kids.product and kids.master_type = 'kids'
            join product_masters as hoodies on products.id = hoodies.product and hoodies.master_type = 'hoodies'
            where products.product_title like '%$searchKey%' or stickers.sku_no like '%$searchKey%' or tshirts.sku_no like '%$searchKey%' or mugs.sku_no like '%$searchKey%'
            or toteBags.sku_no like '%$searchKey%' or cushionCovers.sku_no like '%$searchKey%' or kids.sku_no like '%$searchKey%' or hoodies.sku_no like '%$searchKey%'
            order by $column $order"));
        $results = collect($query);
        $totalcount = count($results);
        $paginate = new LengthAwarePaginator($results->forPage($request->page, $request->per_page)->values(), $totalcount, $request->per_page, $request->page);
        return $this->success($paginate);
    }

    public function priceList($type)
    {
        $sku_type = $type;
        $priceLists = VariantPrice::where(['master' => $sku_type])->get();
        $return = array();
        foreach ($priceLists as $priceList => $index) {
            $data = array();
            $data['key'] = $priceList;
            $data['id'] = $index->id;
            $data['name'] = $index->price;
            $data['default'] = $index->default;
            array_push($return, $data);
        }
        return $return;
    }

    public function artistList()
    {
        $artistLists = Artist::get();
        $return = array();
        foreach ($artistLists as $artistList => $index) {
            $data = array();
            $data['key'] = $artistList;
            $data['id'] = $index->id;
            $data['name'] = $index->code;
            array_push($return, $data);
        }
        return $return;
    }

    public function stickertypeList()
    {
        $stickertypeList = Information::getstickerType();
        return $stickertypeList;
    }

    public function printmodeList()
    {
        $printmodelList = Information::getPrintMode();
        return $printmodelList;
    }

    public function productImageList()
    {
        $productImageList = Information::getImageType();
        return $productImageList;
    }

    public function create(Request $request)
    {
        $keyword_tshirts = Keyword::where('id', $request->keyword)->first()->tshirts;
        $keyword_length = 30 - strlen($keyword_tshirts);

        $validator = Validator::make($request->all(), [
                "product_title" => "required|min:3",
                "stickers_width" => 'required',
                "stickers_height" => 'required',
                "category" => 'required',
                "keyword" => 'required'
            ]
        );
        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        $max_value = 0;
        $masterTypes = ['tshirts', 'stickers', 'mugs', 'tote_bags', 'cushion_covers', 'kids', 'hoodies'];

        foreach ($masterTypes as $master_type) {
            $keyword = strlen(Keyword::where('id', $request->keyword)->first()->$master_type);
            $max_value = max($max_value, $keyword);
        }

        $product_create_validator = strlen($request->product_title) + $max_value + 15;

        if ($product_create_validator > 80) {
            return $this->fail(['message' => "You are out of maximum size in product title. You have $product_create_validator characters, it should be less than 80."]);
        }

        try {
            $now = Carbon::now();
            /* increase setting sku only number*/
            $setting = Setting::first();
            $number = $setting->sku_number;
            $number = sprintf("%05d", $number);
            $setting->sku_number = sprintf("%05d", ($number + 1));
            $setting->save();

            $product = Product::create([
                "product_title" => $request->product_title,
                "keyword" => $request->keyword,
                "category" => $request->category,
                "product_no" => $number,
                "tshirt_price" => $request->p_tshirt,
                "tshirt_print_mode" => $request->tshirt_printmode,
                "artist" => $request->artist,
                "tshirt_image" => $request->tshirt_image,
                "stickers_width" => $request->stickers_width,
                "stickers_height" => $request->stickers_height,
                "stickers_type" => $request->stickers_type,
                "stickers_price" => $request->p_sticker,
                "mug_price" => $request->p_mug,
                "tote_bag_price" => $request->p_totebag,
                "cushion_cover_price" => $request->p_cushioncover,
                "kid_price" => $request->p_kid,
                'hoodie_price' => $request->p_Hoodies,
                "weight" => Setting::first()->weight
            ]);
            $product->creatProductMaster();
            return $this->success(['product' => $product, 'message' => 'Product is created successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Product create failed!']);
        }
    }

    public function delete(Request $request)
    {
        $productid = $request->id;
        $product = Product::find($productid);
        $product->delete();
        /*product master delete*/
        $productmasters = ProductMaster::where(['product' => $productid])->get();
        foreach ($productmasters as $productmaster) {
            $productmaster->delete();
        }
        $productvariants = ProductVariant::where(['product' => $productid])->get();
        foreach ($productvariants as $productvariant) {
            $productvariant->delete();
        }
        return $this->success(['message' => 'The product is deleted successfully!']);
    }

    public function imageSetList()
    {
        $tshirts = ProductImageSet::where('master', 'tshirts')->orderBy('setname')->get();
        $cushionCovers = ProductImageSet::where('master', 'cushionCovers')->orderBy('setname')->get();
        $stickers = ProductImageSet::where('master', 'stickers')->orderBy('setname')->get();
        $mugs = ProductImageSet::where('master', 'mugs')->orderBy('setname')->get();
        $toteBags = ProductImageSet::where('master', 'toteBags')->orderBy('setname')->get();
        $kids = ProductImageSet::where('master', 'kids')->orderBy('setname')->get();
        $hoodies = ProductImageSet::where('master', 'hoodies')->orderBy('setname')->get();

        return $this->success(compact('tshirts', 'cushionCovers', 'stickers', 'mugs', 'toteBags', 'kids', 'hoodies'));
    }

    public function updateImageSet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file1' => 'string',
            'file2' => 'string',
            'file3' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        $productImageSet = ProductImageSet::find($request->id);
        if ($productImageSet) {
            $productImageSet->file1 = $request->file1;
            $productImageSet->file2 = $request->file2;
            $productImageSet->file3 = $request->file3;
            $productImageSet->save();
        }

        $productImageSets = ProductImageSet::all();
        return $this->success(['imageSets' => $productImageSets, 'message' => "The product image set is updated successfully!"]);
    }
}
