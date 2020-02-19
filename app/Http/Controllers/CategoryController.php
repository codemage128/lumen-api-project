<?php


namespace App\Http\Controllers;


use App\Category;
use App\Keyword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $categories = Category::all();
        return $this->success($categories);
    }

    public function list(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Category::where('name', 'like', '%' . $searchKey . '%')
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
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            $category = Category::create($data);

            return $this->success(['category' => $category, 'message' => 'Category is created successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Category create failed!']);
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
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->save();
            return $this->success(['category' => $category, 'message' => 'Category is updated successfully!']);
        } catch (\Exception $exception) {
            return $this->fail(['Category update failed!']);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
            return $this->success(['category' => $category, 'message' => 'Category is deleted successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Category create failed!']);
        }
    }
}
