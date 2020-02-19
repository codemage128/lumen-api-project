<?php


namespace App\Http\Controllers;

use App\Color;
use App\Size;
use App\Type;
use App\Variant;
use App\VariantImage;
use App\VariantPrice;
use App\MerchantPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class VariantController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sizeList(Request $request)
    {
        $skuType = $request->type;
        $sizeList = Size::getList($skuType);
        return $this->success($sizeList);
    }

    public function colorList(Request $request)
    {
        $skuType = $request->type;
        $colorList = Color::getList($skuType);
        return $this->success($colorList);
    }
    public function typeList(Request $request){
        $skyType = $request->type;
        $typeList = Type::getList($skyType);
        return $this->success($typeList);
    }
    public function list_tshirtsList(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Variant::where(['master_type' => 'tshirts'])->where('name', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function list_mugsList(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Variant::where(['master_type' => 'mugs'])->where('name', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function list_hoodiesList(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Variant::where(['master_type' => 'hoodies'])->where('name', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function list_totebagsList(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Variant::where(['master_type' => 'toteBags'])->where('name', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function list_cushioncoversList(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Variant::where(['master_type' => 'cushionCovers'])->where('name', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function list_kidsList(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Variant::where(['master_type' => 'kids'])->where('name', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        try {
            $data = $request->all();
            $gender = $data['gender'];
            $color = $data['color'];
            $size = $data['size'];
            $count = Variant::where(['master_type' => $data['master_type']])->where(['gender' => $gender, 'color' => $color, 'size' => $size])->count();

            if($data['master_type'] == "mugs"){
                $type = $data['type'];
                $count = Variant::where(['master_type' => $data['master_type']])->where(['gender' => $gender, 'color' => $color, 'type' => $type])->count();
            }
            if($data['master_type'] == "cushionCovers"){
                $type = $data['type'];
                $count = Variant::where(['master_type' => $data['master_type']])->where(['gender' => $gender, 'color' => $color, 'type' => $type])->count();
            }
            if ($count == 0) {
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $variant = Variant::create($data);
                return $this->success(['variant' => $variant, 'message' => 'Variant is created successfully!']);
            } else {
                return $this->fail(["Variant already exits."]);
            }
        } catch (\Exception $e) {
            return $this->fail(['Variant create failed!']);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }
        try {
            $variant = Variant::find($request->id);
            $variant->gender = $request->gender;
            $variant->name = $request->name;
            $variant->color = $request->color;
            $variant->size = $request->size;
            $count = Variant::where(['master_type' => $request->master_type])->where(['gender' => $request->gender, 'color' => $request->color, 'size' => $request->size])->count();
            if($request->master_type == "mugs"){
                $variant->type = $request->type;
                $count = Variant::where(['master_type' => $request->master_type])->where(['color' => $request->color, 'type' => $request->type])->count();
            }
            if($request->master_type == "cushionCovers"){
                $variant->type = $request->type;
                $count = Variant::where(['master_type' => $request->master_type])->where(['color' => $request->color, 'type' => $request->type])->count();
            }
            if ($count >= 1) {
                return $this->fail(["Variant already exits."]);
            } else {
                $variant->save();
                return $this->success(['variant' => $variant, 'message' => 'Variant is created successfully!']);
            }
        } catch (\Exception $exception) {
            return $this->fail(['Variant update failed!']);
        }
    }

    public function delete($id)
    {
        try {
            $variant = Variant::find($id);
            $variant->delete();
            return $this->success(['variant' => $variant, 'message' => 'Variant is deleted successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Variant delete failed!']);
        }
    }

    public function imageList()
    {
        $variantImages = VariantImage::all();
        return $this->success($variantImages);
    }

    public function updateImages(Request $request)
    {
        $variantImage = VariantImage::where(['master' => $request->master])->first();
        if ($variantImage) {
            $variantImage->image1 = $request->images[0];
            $variantImage->image2 = $request->images[1];
            $variantImage->image3 = $request->images[2];
            $variantImage->image4 = $request->images[3];
            $variantImage->image5 = $request->images[4];
            $variantImage->image6 = $request->images[5];
            $variantImage->save();
        } else {
            VariantImage::create(['master' => $request->master, 'image1' => $request->image[0],
                'image2' => $request->image[1], 'image3' => $request->image[2],
                'image4' => $request->image[3], 'image5' => $request->image[4]]);
        }

        $variantImages = VariantImage::all();
        return $this->success(['images' => $variantImages, 'message' => "The Variant image is updated successfully!"]);
    }

    public function prices()
    {
        $tshirts = VariantPrice::where('master', 'tshirts')->orderBy('price')->get();
        $cushionCovers = VariantPrice::where('master', 'cushionCovers')->orderBy('price')->get();
        $stickers = VariantPrice::where('master', 'stickers')->orderBy('price')->get();
        $mugs = VariantPrice::where('master', 'mugs')->orderBy('price')->get();
        $toteBags = VariantPrice::where('master', 'toteBags')->orderBy('price')->get();
        $kids = VariantPrice::where('master', 'kids')->orderBy('price')->get();
        $hoodies = VariantPrice::where('master', 'hoodies')->orderBy('price')->get();

        return $this->success(compact('tshirts', 'cushionCovers', 'stickers', 'mugs', 'toteBags', 'kids', 'hoodies'));
    }

    public function addPrice(Request $request) {
        $validator = Validator::make($request->all(), [
                'price' => 'required|numeric',
                'master' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        try {
            $data = $request->all();
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $count = VariantPrice::where(['master' => $data['master'], 'default' => 1])->count();
            if ($count == 0) {
                $data['default'] = 1;
                $variantPrice = VariantPrice::create($data);
            }else {
                $data['default'] = 0;
                $variantPrice = VariantPrice::create($data);
            }
            return $this->success(['variantPrice' => $variantPrice, 'message' => 'Variant price is created successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Variant price create failed!']);
        }
    }

    public function merchantPrices()
    {
        $merchantPrices = MerchantPrice::all();
        return $this->success($merchantPrices);
    }

    public function updateMerchantPrices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prices.*' => 'numeric'
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        $merchantPrice = MerchantPrice::where(['master' => $request->master])->first();
        if ($merchantPrice) {
            $merchantPrice->australia_price = (float)$request->prices[0];
            $merchantPrice->canada_price = (float)$request->prices[1];
            $merchantPrice->usa_price = (float)$request->prices[2];
            $merchantPrice->europe_price = (float)$request->prices[3];
            $merchantPrice->uk_price = (float)$request->prices[4];
            $merchantPrice->save();
        } else {
            MerchantPrice::create(['master' => $request->master, 'australia_price' => (float)$request->prices[0],
                'canada_price' => (float)$request->prices[1], 'usa_price' => (float)$request->prices[2],
                'europe_price' => (float)$request->prices[3], 'uk_price' => (float)$request->prices[4]
            ]);
        }

        $merchantPrices = MerchantPrice::all();
        return $this->success(['prices' => $merchantPrices, 'message' => "The variant merchant price is updated successfully!"]);
    }

    public function deletePrice($id)
    {
        try {
            $variantPrice = VariantPrice::find($id);
            $variantPrice->delete();
            if($variantPrice->default == 1) {
                $variantPrice = VariantPrice::where(['master' => $variantPrice->master])->first();
                $variantPrice->default = 1;
                $variantPrice->save();
            }
            return $this->success(['variantPrice' => $variantPrice, 'message' => 'Variant price is deleted successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Variant price delete failed!']);
        }
    }

    public function defaultPrice($id)
    {
        try {
            $variantPrice = VariantPrice::find($id);
            VariantPrice::where(['master' => $variantPrice->master, 'default' => 1])->update(['default' => 0]);
            $variantPrice->default = 1;
            $variantPrice->save();

            return $this->success(['variantPrice' => $variantPrice, 'message' => 'Variant price is deleted successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Variant price set default failed!']);
        }
    }
}
