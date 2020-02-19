<?php


namespace App\Http\Controllers;

use App\Keyword;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class KeywordController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $keywords = Keyword::all();
        return $this->success($keywords);
    }

    public function list(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;

        $query = Keyword::where('tshirts', 'like', '%' . $searchKey . '%')
            ->orWhere('stickers', 'like', '%' . $searchKey . '%')
            ->orWhere('mugs', 'like', '%' . $searchKey . '%')
            ->orWhere('tote_bags', 'like', '%' . $searchKey . '%')
            ->orWhere('cushion_covers', 'like', '%' . $searchKey . '%')
            ->orWhere('kids', 'like', '%' . $searchKey . '%')
            ->orWhere('hoodies', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);

        return $this->success($query);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'tshirts' => 'required|string',
                'stickers' => 'required|string',
                'mugs' => 'required|string',
                'tote_bags' => 'required|string',
                'cushion_covers' => 'required|string',
                'kids' => 'required|string',
                'hoodies' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        try {
            $keyword = Keyword::find($request->id);

            $keyword->tshirts = $request->tshirts;
            $keyword->stickers = $request->stickers;
            $keyword->mugs = $request->mugs;
            $keyword->tote_bags = $request->tote_bags;
            $keyword->cushion_covers = $request->cushion_covers;
            $keyword->kids = $request->kids;
            $keyword->hoodies = $request->hoodies;

            $keyword->save();

            return $this->success(['keyword' => $keyword, 'message' => 'Keyword is updated successfully!']);
        } catch (\Exception $exception) {
            return $this->fail(['Keyword update failed!']);
        }
    }

    public function delete($id)
    {
        try {
            $keyword = Keyword::find($id);
            $keyword->delete();
            return $this->success(['keyword' => $keyword, 'message' => 'Keyword is deleted successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Keyword delete failed!']);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'tshirts' => 'required|string|unique:keywords',
                'stickers' => 'required|string',
                'mugs' => 'required|string',
                'tote_bags' => 'required|string',
                'cushion_covers' => 'required|string',
                'kids' => 'required|string',
                'hoodies' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        try {
            $data = $request->all();

            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            $keyword = Keyword::create($data);

            return $this->success(['keyword' => $keyword, 'message' => 'Keyword is created successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Keyword create failed!']);
        }
    }
}
